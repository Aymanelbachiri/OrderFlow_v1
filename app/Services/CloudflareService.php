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
     * Create DNS records for Pages custom domain if they don't exist
     * Cloudflare Pages doesn't always create DNS records automatically via API,
     * so we need to create them manually
     */
    public function createPagesDNSRecordsIfNeeded(string $domain, string $zoneId, array $project): array
    {
        try {
            $projectName = $project['name'] ?? $this->pagesProjectName;
            $pagesTarget = $projectName . '.pages.dev';
            
            Log::info('Starting DNS records creation', [
                'domain' => $domain,
                'zone_id' => $zoneId,
                'pages_target' => $pagesTarget,
                'project_name' => $projectName,
            ]);
            
            // Wait a moment for any automatic records to be created
            sleep(2);
            
            // Check existing DNS records
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
                'records' => $existingRecords,
            ]);
            
            // Check if root domain already has a record pointing to Pages
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
                
                // Check root domain
                if ($recordName === $domain || $recordName === '@') {
                    if (stripos($recordContent, 'pages.dev') !== false || stripos($recordContent, $pagesTarget) !== false) {
                        $hasRootRecord = true;
                        Log::info('Root domain record already exists pointing to Pages', [
                            'domain' => $domain,
                            'record' => $record,
                        ]);
                    }
                }
                
                // Check www subdomain
                if ($recordName === 'www.' . $domain || $recordName === 'www') {
                    if (stripos($recordContent, 'pages.dev') !== false || stripos($recordContent, $pagesTarget) !== false) {
                        $hasWWWRecord = true;
                        Log::info('WWW subdomain record already exists pointing to Pages', [
                            'domain' => 'www.' . $domain,
                            'record' => $record,
                        ]);
                    }
                }
            }
            
            $createdRecords = [];
            $failedRecords = [];
            
            // Create root domain CNAME if it doesn't exist
            // Note: For root domains, Cloudflare uses a special CNAME flattening feature
            // We'll create a CNAME record and Cloudflare will handle it
            if (!$hasRootRecord) {
                Log::info('Creating root domain CNAME record', [
                    'domain' => $domain,
                    'target' => $pagesTarget,
                ]);
                
                $rootCnameResult = $this->createDNSRecord(
                    $zoneId,
                    'CNAME',
                    $domain, // Root domain
                    $pagesTarget,
                    3600,
                    true // Proxied
                );
                
                Log::info('Root CNAME creation result', [
                    'success' => $rootCnameResult['success'] ?? false,
                    'result' => $rootCnameResult,
                ]);
                
                if ($rootCnameResult['success']) {
                    $createdRecords[] = 'root';
                    Log::info('Successfully created root domain CNAME for Pages', [
                        'domain' => $domain,
                        'target' => $pagesTarget,
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
                    'target' => $pagesTarget,
                ]);
                
                $wwwCnameResult = $this->createDNSRecord(
                    $zoneId,
                    'CNAME',
                    'www.' . $domain,
                    $pagesTarget,
                    3600,
                    true // Proxied
                );
                
                Log::info('WWW CNAME creation result', [
                    'success' => $wwwCnameResult['success'] ?? false,
                    'result' => $wwwCnameResult,
                ]);
                
                if ($wwwCnameResult['success']) {
                    $createdRecords[] = 'www';
                    Log::info('Successfully created www subdomain CNAME for Pages', [
                        'domain' => 'www.' . $domain,
                        'target' => $pagesTarget,
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
                'pages_target' => $pagesTarget,
                'root_record_created' => !$hasRootRecord && in_array('root', $createdRecords),
                'www_record_created' => !$hasWWWRecord && in_array('www', $createdRecords),
                'root_record_existed' => $hasRootRecord,
                'www_record_existed' => $hasWWWRecord,
                'created_records' => $createdRecords,
                'failed_records' => $failedRecords,
                'message' => count($createdRecords) > 0 
                    ? 'Created ' . count($createdRecords) . ' DNS record(s)' 
                    : 'No new DNS records created (all already exist)',
            ];

            Log::info('DNS records setup completed for Pages domain', $result);
            
            return $result;
        } catch (\Exception $e) {
            $error = [
                'success' => false,
                'domain' => $domain,
                'zone_id' => $zoneId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
            
            Log::error('Failed to create Pages DNS records', $error);
            
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

        // For Direct Upload projects, we need to create with production_branch or use direct upload API
        // Cloudflare Pages Direct Upload doesn't require a Git branch, so we'll create a minimal project
        $result = $this->makeRequest('POST', "/accounts/{$this->accountId}/pages/projects", [
            'name' => $this->pagesProjectName,
            'production_branch' => 'main', // Required field, but not used for direct upload
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
            // Cloudflare Pages Direct Upload requires a two-step process:
            // 1. Create an upload session
            // 2. Upload files to that session
            
            // Step 1: Create upload session
            $sessionUrl = "https://api.cloudflare.com/client/v4/accounts/{$this->accountId}/pages/projects/{$projectId}/upload-tokens";
            
            Log::info('Creating Pages upload session', [
                'project_id' => $projectId,
                'zip_size' => filesize($zipPath),
            ]);

            $sessionResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->withOptions($this->getHttpOptions())
              ->post($sessionUrl);

            $sessionStatusCode = $sessionResponse->status();
            $sessionBody = $sessionResponse->json();

            if (!$sessionResponse->successful()) {
                Log::error('Failed to create upload session', [
                    'project_id' => $projectId,
                    'status_code' => $sessionStatusCode,
                    'response' => $sessionBody,
                ]);

                // Fallback: Try direct deployment endpoint (might work for some accounts)
                return $this->tryDirectDeployment($projectId, $zipPath);
            }

            $uploadToken = $sessionBody['result']['upload_token'] ?? null;
            $uploadUrl = $sessionBody['result']['upload_url'] ?? null;

            if (!$uploadToken || !$uploadUrl) {
                Log::warning('Upload session created but missing token/URL, trying direct deployment', [
                    'session_response' => $sessionBody,
                ]);
                return $this->tryDirectDeployment($projectId, $zipPath);
            }

            // Step 2: Upload the zip file
            Log::info('Uploading files to Pages', [
                'project_id' => $projectId,
                'upload_url' => $uploadUrl,
            ]);

            $uploadResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $uploadToken,
            ])->withOptions($this->getHttpOptions())
              ->attach('file', file_get_contents($zipPath), 'site.zip', [
                  'Content-Type' => 'application/zip',
              ])
              ->post($uploadUrl);

            $uploadStatusCode = $uploadResponse->status();
            $uploadBody = $uploadResponse->json();

            if ($uploadResponse->successful()) {
                Log::info('Pages deployment created successfully', [
                    'project_id' => $projectId,
                    'deployment' => $uploadBody,
                ]);

                return [
                    'success' => true,
                    'data' => $uploadBody,
                    'deployment_id' => $uploadBody['result']['id'] ?? $uploadBody['id'] ?? null,
                ];
            }

            Log::error('Failed to upload files to Pages', [
                'project_id' => $projectId,
                'status_code' => $uploadStatusCode,
                'response' => $uploadBody,
            ]);

            return [
                'success' => false,
                'error' => $uploadBody['errors'][0]['message'] ?? 'Failed to upload deployment',
                'errors' => $uploadBody['errors'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('Exception creating Pages deployment', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Try fallback method
            return $this->tryDirectDeployment($projectId, $zipPath);
        }
    }

    /**
     * Fallback: Try direct deployment endpoint
     */
    private function tryDirectDeployment(string $projectId, string $zipPath): array
    {
        try {
            $deployUrl = "https://api.cloudflare.com/client/v4/accounts/{$this->accountId}/pages/projects/{$projectId}/deployments";
            
            Log::info('Trying direct deployment endpoint', [
                'project_id' => $projectId,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->withOptions($this->getHttpOptions())
              ->attach('file', file_get_contents($zipPath), 'site.zip', [
                  'Content-Type' => 'application/zip',
              ])
              ->post($deployUrl);

            $statusCode = $response->status();
            $responseBody = $response->json();

            if ($response->successful()) {
                Log::info('Direct deployment succeeded', [
                    'project_id' => $projectId,
                    'deployment' => $responseBody,
                ]);

                return [
                    'success' => true,
                    'data' => $responseBody,
                    'deployment_id' => $responseBody['result']['id'] ?? $responseBody['id'] ?? null,
                ];
            }

            Log::error('Direct deployment also failed', [
                'project_id' => $projectId,
                'status_code' => $statusCode,
                'response' => $responseBody,
            ]);

            return [
                'success' => false,
                'error' => $responseBody['errors'][0]['message'] ?? 'Failed to create deployment. Please check API token permissions and try deploying manually via Cloudflare dashboard.',
                'errors' => $responseBody['errors'] ?? [],
                'suggestion' => 'You may need to deploy manually via Cloudflare Dashboard > Pages > Upload files, or use Wrangler CLI.',
            ];
        } catch (\Exception $e) {
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

