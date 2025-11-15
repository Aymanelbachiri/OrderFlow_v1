<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareService
{
    private string $apiToken;
    private string $accountId;
    private string $pagesProjectName;
    private string $baseUrl;

    public function __construct()
    {
        // Check database settings first, then fall back to config/env
        $this->apiToken = \App\Models\SystemSetting::get('cloudflare_api_token') 
            ?: config('services.cloudflare.api_token', '');
        $this->accountId = \App\Models\SystemSetting::get('cloudflare_account_id') 
            ?: config('services.cloudflare.account_id', '');
        $this->pagesProjectName = \App\Models\SystemSetting::get('cloudflare_pages_project_name') 
            ?: config('services.cloudflare.pages_project_name', 'shield-domains');
        $this->baseUrl = config('services.cloudflare.api_base_url', 'https://api.cloudflare.com/client/v4');
    }

    /**
     * Get HTTP client options with SSL configuration
     */
    private function getHttpOptions(): array
    {
        $options = [
            'timeout' => 30,
            'connect_timeout' => 10,
        ];

        // SSL verification - disable in local/dev environments
        $isLocal = app()->environment('local') || app()->environment('development');
        
        if ($isLocal) {
            // Disable SSL verification in local/development only
            $options['verify'] = false;
        } else {
            // Production: Try to find CA bundle for SSL verification
            $caBundlePaths = [
                '/etc/ssl/certs/ca-certificates.crt', // Linux
                '/etc/pki/tls/certs/ca-bundle.crt', // CentOS/RHEL
                '/usr/local/etc/openssl/cert.pem', // macOS
                base_path('vendor/composer/ca-bundle/ca-bundle.crt'), // Composer CA bundle
                'C:/curl-ca-bundle.crt', // Windows (if manually placed)
            ];
            
            $caBundleFound = false;
            foreach ($caBundlePaths as $caPath) {
                if (file_exists($caPath)) {
                    $options['verify'] = $caPath;
                    $caBundleFound = true;
                    break;
                }
            }
            
            // If no CA bundle found, still verify but let system handle it
            if (!$caBundleFound) {
                $options['verify'] = true;
            }
        }

        return $options;
    }

    /**
     * Make authenticated API request to Cloudflare
     */
    private function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        try {
            // Validate API token is set
            if (empty($this->apiToken)) {
                Log::error('Cloudflare API token is empty');
                return [
                    'success' => false,
                    'error' => 'Cloudflare API token is not configured. Please configure it in Settings.',
                ];
            }

            $httpOptions = $this->getHttpOptions();
            
            $fullUrl = $this->baseUrl . $endpoint;
            
            Log::debug('Making Cloudflare API request', [
                'method' => $method,
                'endpoint' => $endpoint,
                'full_url' => $fullUrl,
                'has_data' => !empty($data),
                'token_length' => strlen($this->apiToken),
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->withOptions($httpOptions)
              ->{strtolower($method)}($fullUrl, $data);

            $statusCode = $response->status();
            $responseBody = $response->json();
            
            Log::debug('Cloudflare API response', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status_code' => $statusCode,
                'success' => $response->successful(),
                'has_errors' => isset($responseBody['errors']),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseBody,
                ];
            }

            // Handle authentication errors specifically
            if ($statusCode === 401 || $statusCode === 403) {
                $errorMessage = $responseBody['errors'][0]['message'] ?? 'Authentication error';
                
                Log::error('Cloudflare API Authentication Error', [
                    'method' => $method,
                    'endpoint' => $endpoint,
                    'status_code' => $statusCode,
                    'error' => $errorMessage,
                    'errors' => $responseBody['errors'] ?? [],
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Authentication error: ' . $errorMessage . '. Please check your API token permissions in Cloudflare dashboard.',
                    'errors' => $responseBody['errors'] ?? [],
                    'status_code' => $statusCode,
                ];
            }

            $errorMessage = $responseBody['errors'][0]['message'] ?? 'Unknown error';
            
            Log::warning('Cloudflare API request failed', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status_code' => $statusCode,
                'error' => $errorMessage,
                'errors' => $responseBody['errors'] ?? [],
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'errors' => $responseBody['errors'] ?? [],
                'status_code' => $statusCode,
            ];
        } catch (\Exception $e) {
            Log::error('Cloudflare API Exception', [
                'method' => $method,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get zone by domain name
     */
    public function getZoneByDomain(string $domain): array
    {
        $result = $this->makeRequest('GET', '/zones', [
            'name' => $domain,
        ]);

        if ($result['success']) {
            $zones = $result['data']['result'] ?? [];
            if (!empty($zones)) {
                $zone = $zones[0]; // Get first matching zone
                return [
                    'success' => true,
                    'zone_id' => $zone['id'],
                    'nameservers' => $zone['name_servers'] ?? [],
                    'zone' => $zone,
                ];
            }
        }

        return [
            'success' => false,
            'error' => 'Zone not found',
        ];
    }

    /**
     * Add a domain to Cloudflare (create zone) or get existing zone
     */
    public function addZone(string $domain): array
    {
        // First check if zone already exists
        $existingZone = $this->getZoneByDomain($domain);
        if ($existingZone['success']) {
            return [
                'success' => true,
                'zone_id' => $existingZone['zone_id'],
                'nameservers' => $existingZone['nameservers'],
                'existing' => true,
            ];
        }

        // Zone doesn't exist, create it
        $result = $this->makeRequest('POST', '/zones', [
            'name' => $domain,
            'account' => [
                'id' => $this->accountId,
            ],
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'zone_id' => $result['data']['result']['id'],
                'nameservers' => $result['data']['result']['name_servers'] ?? [],
                'existing' => false,
            ];
        }

        // Check if error is because zone already exists
        $errorMessage = $result['error'] ?? '';
        if (stripos($errorMessage, 'already exists') !== false || stripos($errorMessage, 'duplicate') !== false) {
            // Zone exists but we didn't find it, try getting it again
            $existingZone = $this->getZoneByDomain($domain);
            if ($existingZone['success']) {
                return [
                    'success' => true,
                    'zone_id' => $existingZone['zone_id'],
                    'nameservers' => $existingZone['nameservers'],
                    'existing' => true,
                ];
            }
        }

        return $result;
    }

    /**
     * Get nameservers for a zone
     */
    public function getNameservers(string $zoneId): array
    {
        $result = $this->makeRequest('GET', "/zones/{$zoneId}");

        if ($result['success']) {
            return [
                'success' => true,
                'nameservers' => $result['data']['result']['name_servers'] ?? [],
            ];
        }

        return $result;
    }

    /**
     * Create custom domain binding in Cloudflare Pages
     */
    public function createPagesCustomDomain(string $domain, string $zoneId): array
    {
        // First, get the project ID
        $projectResult = $this->getPagesProject();
        if (!$projectResult['success']) {
            return $projectResult;
        }

        $projectId = $projectResult['project_id'];

        // Create custom domain
        $result = $this->makeRequest('POST', "/accounts/{$this->accountId}/pages/projects/{$projectId}/domains", [
            'domain' => $domain,
        ]);

        // If successful, Cloudflare Pages should automatically create DNS records
        // But we'll verify and create them if needed
        if ($result['success']) {
            // Wait a moment for Cloudflare to process
            sleep(2);
            
            // The response might include DNS target information
            // For now, we'll check if DNS records exist and create them if needed
            $dnsResult = $this->createPagesDNSRecordsIfNeeded($domain, $zoneId, $projectResult['project']);
            Log::info('DNS records creation result', $dnsResult);
        }

        return $result;
    }

    /**
     * Setup DNS records for shield domain pointing to main SaaS server
     * Creates CNAME records pointing to the main application server
     */
    public function createShieldDomainDNSRecords(string $domain, string $zoneId): array
    {
        try {
            // Get main SaaS server hostname from APP_URL
            $appUrl = config('app.url', env('APP_URL', 'http://localhost'));
            $mainServerHost = parse_url($appUrl, PHP_URL_HOST);
            
            if (!$mainServerHost) {
                return [
                    'success' => false,
                    'error' => 'Could not determine main server hostname from APP_URL. Please configure APP_URL in your .env file.',
                ];
            }
            
            Log::info('Starting DNS records creation for shield domain', [
                'domain' => $domain,
                'zone_id' => $zoneId,
                'target_host' => $mainServerHost,
            ]);
            
            sleep(2); // Wait for any automatic records
            
            Log::info('Fetching existing DNS records', ['zone_id' => $zoneId]);
            $allRecords = $this->getDNSRecords($zoneId);
            
            if (!$allRecords['success']) {
                Log::error('Failed to fetch DNS records', [
                    'zone_id' => $zoneId,
                    'error' => $allRecords['error'] ?? 'Unknown error',
                ]);
                return [
                    'success' => false,
                    'error' => 'Failed to fetch existing DNS records: ' . ($allRecords['error'] ?? 'Unknown error'),
                ];
            }
            
            $existingRecords = $allRecords['data']['result'] ?? [];
            Log::info('Found existing DNS records', [
                'zone_id' => $zoneId,
                'count' => count($existingRecords),
            ]);
            
            $hasRootRecord = false;
            $hasWWWRecord = false;
            
            foreach ($existingRecords as $record) {
                $recordName = rtrim($record['name'], '.');
                $recordContent = $record['content'] ?? '';
                
                Log::debug('Checking DNS record', [
                    'name' => $recordName,
                    'type' => $record['type'] ?? 'unknown',
                    'content' => $recordContent,
                ]);
                
                // Check root domain - look for records pointing to main server
                if ($recordName === $domain || $recordName === '@') {
                    if ($recordContent === $mainServerHost || stripos($recordContent, $mainServerHost) !== false) {
                        $hasRootRecord = true;
                        Log::info('Root domain record already exists pointing to main server', [
                            'domain' => $domain,
                            'target' => $recordContent,
                        ]);
                    }
                }
                
                // Check www subdomain
                if ($recordName === 'www.' . $domain || $recordName === 'www') {
                    if ($recordContent === $mainServerHost || stripos($recordContent, $mainServerHost) !== false) {
                        $hasWWWRecord = true;
                        Log::info('WWW subdomain record already exists pointing to main server', [
                            'domain' => 'www.' . $domain,
                            'target' => $recordContent,
                        ]);
                    }
                }
            }
            
            $createdRecords = [];
            $failedRecords = [];
            
            // Create root domain CNAME if it doesn't exist
            // Note: For root domains, Cloudflare uses a special CNAME flattening feature
            if (!$hasRootRecord) {
                Log::info('Creating root domain CNAME record', [
                    'domain' => $domain,
                    'target' => $mainServerHost,
                ]);
                
                $rootCnameResult = $this->createDNSRecord(
                    $zoneId,
                    'CNAME',
                    $domain, // Root domain
                    $mainServerHost,
                    3600,
                    true // Proxied
                );
                
                Log::info('Root CNAME creation result', [
                    'success' => $rootCnameResult['success'] ?? false,
                    'result' => $rootCnameResult,
                ]);
                
                if ($rootCnameResult['success']) {
                    $createdRecords[] = 'root';
                    Log::info('Successfully created root domain CNAME', [
                        'domain' => $domain,
                        'target' => $mainServerHost,
                        'record_id' => $rootCnameResult['data']['result']['id'] ?? null,
                    ]);
                } else {
                    $failedRecords[] = 'root';
                    Log::error('Failed to create root CNAME', [
                        'domain' => $domain,
                        'error' => $rootCnameResult['error'] ?? 'Unknown error',
                        'full_response' => $rootCnameResult,
                    ]);
                }
            } else {
                Log::info('Root domain record already exists, skipping creation');
            }
            
            // Create www subdomain CNAME if it doesn't exist
            if (!$hasWWWRecord) {
                Log::info('Creating www subdomain CNAME record', [
                    'domain' => 'www.' . $domain,
                    'target' => $mainServerHost,
                ]);
                
                $wwwCnameResult = $this->createDNSRecord(
                    $zoneId,
                    'CNAME',
                    'www.' . $domain,
                    $mainServerHost,
                    3600,
                    true // Proxied
                );
                
                Log::info('WWW CNAME creation result', [
                    'success' => $wwwCnameResult['success'] ?? false,
                    'result' => $wwwCnameResult,
                ]);
                
                if ($wwwCnameResult['success']) {
                    $createdRecords[] = 'www';
                    Log::info('Successfully created www subdomain CNAME', [
                        'domain' => 'www.' . $domain,
                        'target' => $mainServerHost,
                        'record_id' => $wwwCnameResult['data']['result']['id'] ?? null,
                    ]);
                } else {
                    $failedRecords[] = 'www';
                    Log::error('Failed to create www CNAME', [
                        'domain' => 'www.' . $domain,
                        'error' => $wwwCnameResult['error'] ?? 'Unknown error',
                        'full_response' => $wwwCnameResult,
                    ]);
                }
            } else {
                Log::info('WWW subdomain record already exists, skipping creation');
            }
            
            $result = [
                'success' => true,
                'domain' => $domain,
                'zone_id' => $zoneId,
                'target_host' => $mainServerHost,
                'root_record_created' => !$hasRootRecord && in_array('root', $createdRecords),
                'www_record_created' => !$hasWWWRecord && in_array('www', $createdRecords),
                'root_record_existed' => $hasRootRecord,
                'www_record_existed' => $hasWWWRecord,
                'created_records' => $createdRecords,
                'failed_records' => $failedRecords,
                'message' => count($createdRecords) > 0 
                    ? 'Created ' . count($createdRecords) . ' DNS record(s) pointing to main server' 
                    : 'No new DNS records created (all already exist)',
            ];

            Log::info('DNS records setup completed for shield domain', $result);
            
            return $result;
        } catch (\Exception $e) {
            $error = [
                'success' => false,
                'domain' => $domain,
                'zone_id' => $zoneId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
            
            Log::error('Failed to create shield domain DNS records', $error);
            
            return $error;
        }
    }

    /**
     * Trigger DNS record scan for a zone
     */
    public function triggerDNSScan(string $zoneId): array
    {
        return $this->makeRequest('POST', "/zones/{$zoneId}/dns_records/scan/trigger");
    }

    /**
     * Get DNS records review after scan
     */
    public function getDNSRecordsReview(string $zoneId): array
    {
        return $this->makeRequest('GET', "/zones/{$zoneId}/dns_records/scan/review");
    }

    /**
     * Create DNS record in a zone
     */
    public function createDNSRecord(string $zoneId, string $type, string $name, string $content, int $ttl = 3600, bool $proxied = true): array
    {
        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
        ];

        // Only set proxied for A and CNAME records
        if (in_array($type, ['A', 'CNAME']) && $proxied) {
            $data['proxied'] = true;
            // When proxied, TTL is automatically set to 1 (auto) by Cloudflare
        } else {
            // Only set TTL if not proxied
            $data['ttl'] = $ttl;
        }

        Log::info('Creating DNS record', [
            'zone_id' => $zoneId,
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied,
            'data' => $data,
        ]);

        $result = $this->makeRequest('POST', "/zones/{$zoneId}/dns_records", $data);
        
        Log::info('DNS record creation response', [
            'zone_id' => $zoneId,
            'name' => $name,
            'success' => $result['success'] ?? false,
            'result' => $result,
        ]);

        return $result;
    }

    /**
     * Get DNS records for a zone
     */
    public function getDNSRecords(string $zoneId, string $type = null, string $name = null): array
    {
        $params = [];
        if ($type) {
            $params['type'] = $type;
        }
        if ($name) {
            $params['name'] = $name;
        }

        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        return $this->makeRequest('GET', "/zones/{$zoneId}/dns_records" . $queryString);
    }

    /**
     * Setup DNS records for Cloudflare Pages domain
     * This method triggers a DNS scan to import existing records
     */
    public function setupPagesDNSRecords(string $domain, string $zoneId): array
    {
        try {
            // Trigger DNS scan to import existing records
            $scanResult = $this->triggerDNSScan($zoneId);
            
            Log::info('DNS scan triggered', [
                'zone_id' => $zoneId,
                'domain' => $domain,
                'scan_result' => $scanResult,
            ]);

            return [
                'success' => true,
                'message' => 'DNS scan triggered successfully. Existing records will be imported.',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to setup DNS records', [
                'zone_id' => $zoneId,
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create Cloudflare Pages project
     */
    public function createPagesProject(): array
    {
        Log::info('Creating Cloudflare Pages project', [
            'project_name' => $this->pagesProjectName,
            'account_id' => $this->accountId,
        ]);

        // For Direct Upload projects, we need to create with production_branch
        // Note: Cloudflare API requires production_branch even for Direct Upload projects
        // The project type is determined by how you deploy, not how you create it
        $result = $this->makeRequest('POST', "/accounts/{$this->accountId}/pages/projects", [
            'name' => $this->pagesProjectName,
            'production_branch' => 'main', // Required field
        ]);

        if ($result['success']) {
            $projectId = $result['data']['result']['id'] ?? null;
            $project = $result['data']['result'] ?? [];
            
            Log::info('Cloudflare Pages project created successfully', [
                'project_name' => $this->pagesProjectName,
                'project_id' => $projectId,
            ]);
            
            return [
                'success' => true,
                'project_id' => $projectId,
                'project' => $project,
            ];
        }

        // Check if project already exists with different name
        $errorMessage = $result['error'] ?? 'Unknown error';
        if (stripos($errorMessage, 'already exists') !== false || stripos($errorMessage, 'duplicate') !== false) {
            // Try to find existing project
            Log::info('Project might already exist, searching for it', [
                'project_name' => $this->pagesProjectName,
            ]);
            
            $listResult = $this->makeRequest('GET', "/accounts/{$this->accountId}/pages/projects");
            if ($listResult['success']) {
                $projects = $listResult['data']['result'] ?? [];
                foreach ($projects as $project) {
                    if ($project['name'] === $this->pagesProjectName) {
                        Log::info('Found existing project', [
                            'project_name' => $this->pagesProjectName,
                            'project_id' => $project['id'],
                        ]);
                        
                        return [
                            'success' => true,
                            'project_id' => $project['id'],
                            'project' => $project,
                        ];
                    }
                }
            }
        }

        Log::error('Failed to create Cloudflare Pages project', [
            'project_name' => $this->pagesProjectName,
            'error' => $errorMessage,
            'errors' => $result['errors'] ?? [],
        ]);

        return $result;
    }

    /**
     * Deploy files to Cloudflare Pages using Direct Upload
     */
    public function deployPagesProject(string $templateName = 'template-1'): array
    {
        try {
            // Get project first - ensure it exists
            $projectResult = $this->getPagesProject();
            if (!$projectResult['success']) {
                Log::error('Failed to get Pages project before deployment', [
                    'error' => $projectResult['error'] ?? 'Unknown error',
                    'project_name' => $this->pagesProjectName,
                ]);
                
                // Try to create the project explicitly
                $createResult = $this->createPagesProject();
                if ($createResult['success']) {
                    $projectResult = $createResult;
                } else {
                    return [
                        'success' => false,
                        'error' => 'Failed to get or create Pages project: ' . ($projectResult['error'] ?? 'Unknown error') . '. ' . ($createResult['error'] ?? ''),
                    ];
                }
            }

            $projectId = $projectResult['project_id'] ?? null;
            
            if (!$projectId) {
                return [
                    'success' => false,
                    'error' => 'Project ID not found in project result. Please check Cloudflare Pages project exists.',
                ];
            }
            
            $templatePath = public_path("templates/{$templateName}");

            if (!file_exists($templatePath)) {
                return [
                    'success' => false,
                    'error' => "Template directory not found: {$templatePath}",
                ];
            }

            // Create a zip file of the template
            $zipPath = storage_path("app/pages-deploy-{$templateName}-" . time() . '.zip');
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                return [
                    'success' => false,
                    'error' => 'Failed to create zip file',
                ];
            }

            // Add all files from template directory to zip
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($templatePath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($templatePath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();

            // Upload to Cloudflare Pages using Direct Upload
            // Note: Cloudflare Pages Direct Upload requires a special endpoint
            // We'll use the deployment API
            $deploymentResult = $this->createPagesDeployment($projectId, $zipPath);

            // Clean up zip file
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }

            return $deploymentResult;
        } catch (\Exception $e) {
            Log::error('Failed to deploy Pages project', [
                'template' => $templateName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a Pages deployment from uploaded files
     * Uses Cloudflare Pages Direct Upload API
     */
    private function createPagesDeployment(string $projectId, string $zipPath): array
    {
        try {
            // Cloudflare Pages Direct Upload API endpoint
            // For Direct Upload projects, we use the deployments endpoint with multipart/form-data
            $deployUrl = "https://api.cloudflare.com/client/v4/accounts/{$this->accountId}/pages/projects/{$projectId}/deployments";
            
            Log::info('Uploading deployment to Cloudflare Pages (Direct Upload)', [
                'project_id' => $projectId,
                'zip_size' => filesize($zipPath),
                'endpoint' => $deployUrl,
            ]);

            // Read zip file content
            $zipContent = file_get_contents($zipPath);
            
            // Use multipart form data with the file
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->withOptions($this->getHttpOptions())
              ->attach('file', $zipContent, 'site.zip', [
                  'Content-Type' => 'application/zip',
              ])
              ->post($deployUrl);

            $statusCode = $response->status();
            $responseBody = $response->json();

            Log::debug('Pages deployment API response', [
                'project_id' => $projectId,
                'status_code' => $statusCode,
                'response_keys' => array_keys($responseBody ?? []),
            ]);

            if ($response->successful()) {
                $deploymentId = $responseBody['result']['id'] ?? $responseBody['id'] ?? null;
                
                Log::info('Pages deployment created successfully', [
                    'project_id' => $projectId,
                    'deployment_id' => $deploymentId,
                    'response' => $responseBody,
                ]);

                return [
                    'success' => true,
                    'data' => $responseBody,
                    'deployment_id' => $deploymentId,
                ];
            }

            // Check if it's a project type issue
            $errorMessage = $responseBody['errors'][0]['message'] ?? 'Failed to create deployment';
            $errorCode = $responseBody['errors'][0]['code'] ?? null;

            Log::error('Failed to create Pages deployment', [
                'project_id' => $projectId,
                'status_code' => $statusCode,
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
                'response' => $responseBody,
            ]);

            // If project not found, it might be a Git-based project, not Direct Upload
            if ($statusCode === 404 || $errorCode === 8000007) {
                // Try to get project details to check its type
                $projectDetails = $this->getPagesProject();
                $projectType = 'unknown';
                if ($projectDetails['success'] && isset($projectDetails['project'])) {
                    $project = $projectDetails['project'];
                    $projectType = $project['source']['type'] ?? 'unknown';
                }
                
                Log::warning('Pages deployment failed - project may be Git-based', [
                    'project_id' => $projectId,
                    'project_type' => $projectType,
                    'error_code' => $errorCode,
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Project not found or is not a Direct Upload project. The project exists but cannot accept direct file uploads.',
                    'errors' => $responseBody['errors'] ?? [],
                    'suggestion' => 'Please create a new "Direct Upload" project manually: Go to Cloudflare Dashboard > Pages > Create a project > Choose "Direct Upload" > Name it: ' . $this->pagesProjectName . '. Then delete the old project if needed.',
                    'project_type' => $projectType,
                ];
            }

            return [
                'success' => false,
                'error' => $errorMessage,
                'errors' => $responseBody['errors'] ?? [],
                'status_code' => $statusCode,
            ];
        } catch (\Exception $e) {
            Log::error('Exception creating Pages deployment', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'suggestion' => 'Please deploy manually via Cloudflare Dashboard > Pages > Upload files.',
            ];
        }
    }


    /**
     * Get Cloudflare Pages project (create if doesn't exist)
     */
    public function getPagesProject(): array
    {
        $result = $this->makeRequest('GET', "/accounts/{$this->accountId}/pages/projects");

        if ($result['success']) {
            $projects = $result['data']['result'] ?? [];
            
            // Find project by name
            foreach ($projects as $project) {
                if ($project['name'] === $this->pagesProjectName) {
                    // Log project details for debugging
                    Log::info('Found Pages project', [
                        'project_id' => $project['id'],
                        'project_name' => $project['name'],
                        'project_keys' => array_keys($project),
                        'has_source' => isset($project['source']),
                        'source_type' => $project['source']['type'] ?? 'unknown',
                    ]);
                    
                    return [
                        'success' => true,
                        'project_id' => $project['id'],
                        'project' => $project,
                    ];
                }
            }

            // Project not found, try to create it
            Log::info('Pages project not found, attempting to create', [
                'project_name' => $this->pagesProjectName,
            ]);

            $createResult = $this->createPagesProject();
            
            if ($createResult['success']) {
                Log::info('Pages project created successfully', [
                    'project_name' => $this->pagesProjectName,
                    'project_id' => $createResult['project_id'],
                ]);
                
                return $createResult;
            }

            return [
                'success' => false,
                'error' => "Pages project '{$this->pagesProjectName}' not found and could not be created: " . ($createResult['error'] ?? 'Unknown error'),
            ];
        }

        return $result;
    }

    /**
     * Verify DNS configuration by checking if nameservers are set
     */
    public function verifyDNS(string $domain): array
    {
        try {
            // Use DNS lookup to check nameservers
            $nameservers = dns_get_record($domain, DNS_NS);
            
            if (empty($nameservers)) {
                return [
                    'success' => false,
                    'configured' => false,
                    'message' => 'No nameservers found',
                ];
            }

            // Get zone info to compare nameservers
            $zonesResult = $this->makeRequest('GET', '/zones', [
                'name' => $domain,
            ]);

            if (!$zonesResult['success']) {
                return [
                    'success' => false,
                    'configured' => false,
                    'message' => 'Could not verify zone',
                ];
            }

            $zones = $zonesResult['data']['result'] ?? [];
            if (empty($zones)) {
                return [
                    'success' => false,
                    'configured' => false,
                    'message' => 'Zone not found in Cloudflare',
                ];
            }

            $zone = $zones[0];
            $expectedNameservers = $zone['name_servers'] ?? [];
            $actualNameservers = array_map(function ($ns) {
                return strtolower($ns['target'] ?? '');
            }, $nameservers);

            $configured = !empty(array_intersect(
                array_map('strtolower', $expectedNameservers),
                $actualNameservers
            ));

            // Get actual nameservers found for better messaging
            $foundNameservers = array_unique($actualNameservers);
            $expectedNameserversLower = array_map('strtolower', $expectedNameservers);

            return [
                'success' => true,
                'configured' => $configured,
                'expected_nameservers' => $expectedNameservers,
                'found_nameservers' => $foundNameservers,
                'actual_nameservers' => $actualNameservers,
                'message' => $configured 
                    ? 'Nameservers are correctly configured' 
                    : 'Nameservers do not match. Expected: ' . implode(', ', $expectedNameservers) . '. Found: ' . implode(', ', $foundNameservers),
            ];
        } catch (\Exception $e) {
            Log::error('DNS Verification Error', [
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'configured' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a zone from Cloudflare
     */
    public function deleteZone(string $zoneId): array
    {
        return $this->makeRequest('DELETE', "/zones/{$zoneId}");
    }

    /**
     * Check if Cloudflare is configured
     */
    public function isConfigured(): bool
    {
        // Check database settings first, then fall back to config/env
        $apiToken = \App\Models\SystemSetting::get('cloudflare_api_token') 
            ?: config('services.cloudflare.api_token', '');
        $accountId = \App\Models\SystemSetting::get('cloudflare_account_id') 
            ?: config('services.cloudflare.account_id', '');
        $pagesProjectName = \App\Models\SystemSetting::get('cloudflare_pages_project_name') 
            ?: config('services.cloudflare.pages_project_name', 'shield-domains');
        
        return !empty($apiToken) 
            && !empty($accountId) 
            && !empty($pagesProjectName);
    }
}

