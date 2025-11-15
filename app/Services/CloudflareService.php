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
            
            // Wait a moment for any automatic records to be created
            sleep(2);
            
            // Check existing DNS records
            $allRecords = $this->getDNSRecords($zoneId);
            $existingRecords = $allRecords['success'] ? ($allRecords['data']['result'] ?? []) : [];
            
            // Check if root domain already has a record pointing to Pages
            $hasRootRecord = false;
            $hasWWWRecord = false;
            
            foreach ($existingRecords as $record) {
                $recordName = rtrim($record['name'], '.');
                $recordContent = $record['content'] ?? '';
                
                // Check root domain
                if ($recordName === $domain || $recordName === '@') {
                    if (stripos($recordContent, 'pages.dev') !== false || stripos($recordContent, $pagesTarget) !== false) {
                        $hasRootRecord = true;
                    }
                }
                
                // Check www subdomain
                if ($recordName === 'www.' . $domain || $recordName === 'www') {
                    if (stripos($recordContent, 'pages.dev') !== false || stripos($recordContent, $pagesTarget) !== false) {
                        $hasWWWRecord = true;
                    }
                }
            }
            
            // Create root domain CNAME if it doesn't exist
            // Note: For root domains, Cloudflare uses a special CNAME flattening feature
            // We'll create a CNAME record and Cloudflare will handle it
            if (!$hasRootRecord) {
                $rootCnameResult = $this->createDNSRecord(
                    $zoneId,
                    'CNAME',
                    $domain, // Root domain
                    $pagesTarget,
                    3600,
                    true // Proxied
                );
                
                if ($rootCnameResult['success']) {
                    Log::info('Created root domain CNAME for Pages', [
                        'domain' => $domain,
                        'target' => $pagesTarget,
                    ]);
                } else {
                    // If CNAME fails for root (some DNS providers don't allow it),
                    // try creating an A record pointing to Cloudflare's IPs
                    // But actually, Cloudflare handles CNAME flattening automatically
                    Log::warning('Failed to create root CNAME, will try alternative', [
                        'domain' => $domain,
                        'error' => $rootCnameResult['error'] ?? 'Unknown error',
                    ]);
                }
            }
            
            // Create www subdomain CNAME if it doesn't exist
            if (!$hasWWWRecord) {
                $wwwCnameResult = $this->createDNSRecord(
                    $zoneId,
                    'CNAME',
                    'www.' . $domain,
                    $pagesTarget,
                    3600,
                    true // Proxied
                );
                
                if ($wwwCnameResult['success']) {
                    Log::info('Created www subdomain CNAME for Pages', [
                        'domain' => 'www.' . $domain,
                        'target' => $pagesTarget,
                    ]);
                } else {
                    Log::warning('Failed to create www CNAME', [
                        'domain' => 'www.' . $domain,
                        'error' => $wwwCnameResult['error'] ?? 'Unknown error',
                    ]);
                }
            }
            
            $result = [
                'success' => true,
                'domain' => $domain,
                'zone_id' => $zoneId,
                'pages_target' => $pagesTarget,
                'root_record_created' => !$hasRootRecord,
                'www_record_created' => !$hasWWWRecord,
                'root_record_existed' => $hasRootRecord,
                'www_record_existed' => $hasWWWRecord,
                'message' => 'DNS records configuration completed',
            ];

            Log::info('DNS records setup completed for Pages domain', $result);
            
            return $result;
        } catch (\Exception $e) {
            $error = [
                'success' => false,
                'domain' => $domain,
                'zone_id' => $zoneId,
                'error' => $e->getMessage(),
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
            'ttl' => $ttl,
        ];

        // Only set proxied for A and CNAME records
        if (in_array($type, ['A', 'CNAME']) && $proxied) {
            $data['proxied'] = true;
        }

        return $this->makeRequest('POST', "/zones/{$zoneId}/dns_records", $data);
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
     * Get Cloudflare Pages project
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

            return [
                'success' => false,
                'error' => "Pages project '{$this->pagesProjectName}' not found",
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

