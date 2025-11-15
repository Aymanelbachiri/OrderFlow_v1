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
     * Add a domain to Cloudflare (create zone)
     */
    public function addZone(string $domain): array
    {
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
            ];
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

        return $result;
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

