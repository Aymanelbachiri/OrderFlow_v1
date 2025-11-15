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
            $httpOptions = $this->getHttpOptions();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->withOptions($httpOptions)
              ->{strtolower($method)}($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['errors'][0]['message'] ?? 'Unknown error',
                'errors' => $response->json()['errors'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('Cloudflare API Error', [
                'method' => $method,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
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
        $result = $this->makeRequest('POST', "/accounts/{$this->accountId}/pages/projects", [
            'name' => $this->pagesProjectName,
            'production_branch' => 'main', // Default branch
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'project_id' => $result['data']['result']['id'] ?? null,
                'project' => $result['data']['result'] ?? [],
            ];
        }

        return $result;
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

