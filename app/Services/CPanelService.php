<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CPanelService
{
    private string $host;
    private string $username;
    private string $password;
    private string $port;
    private bool $useSSL;

    public function __construct()
    {
        // Get credentials from SystemSetting or config
        $this->host = \App\Models\SystemSetting::get('cpanel_host') 
            ?: config('services.cpanel.host', '');
        $this->username = \App\Models\SystemSetting::get('cpanel_username') 
            ?: config('services.cpanel.username', '');
        $this->password = \App\Models\SystemSetting::get('cpanel_password') 
            ?: config('services.cpanel.password', '');
        $this->port = \App\Models\SystemSetting::get('cpanel_port') 
            ?: config('services.cpanel.port', '2083');
        $this->useSSL = (bool) (\App\Models\SystemSetting::get('cpanel_use_ssl') 
            ?: config('services.cpanel.use_ssl', true));
    }

    /**
     * Check if cPanel is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->host) && !empty($this->username) && !empty($this->password);
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
            $options['verify'] = false;
        } else {
            // Production: Try to find CA bundle for SSL verification
            $caBundlePaths = [
                '/etc/ssl/certs/ca-certificates.crt',
                '/etc/pki/tls/certs/ca-bundle.crt',
                '/usr/local/etc/openssl/cert.pem',
                base_path('vendor/composer/ca-bundle/ca-bundle.crt'),
                'C:/curl-ca-bundle.crt',
            ];
            
            foreach ($caBundlePaths as $caPath) {
                if (file_exists($caPath)) {
                    $options['verify'] = $caPath;
                    break;
                }
            }
        }

        return $options;
    }

    /**
     * Make a cPanel API request
     */
    private function makeRequest(string $module, string $function, array $params = []): array
    {
        try {
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'error' => 'cPanel is not configured. Please configure it in Settings.',
                ];
            }

            $protocol = $this->useSSL ? 'https' : 'http';
            // cPanel UAPI endpoint format: /execute/Module/function
            $url = "{$protocol}://{$this->host}:{$this->port}/execute/{$module}/{$function}";

            Log::info('Making cPanel API request', [
                'module' => $module,
                'function' => $function,
                'url' => $url,
                'has_params' => !empty($params),
            ]);

            $response = Http::withBasicAuth($this->username, $this->password)
                ->withOptions($this->getHttpOptions())
                ->get($url, $params);

            $statusCode = $response->status();
            $responseBody = $response->json();

            Log::debug('cPanel API response', [
                'module' => $module,
                'function' => $function,
                'status_code' => $statusCode,
                'success' => $responseBody['status'] ?? 0 === 1,
            ]);

            if ($statusCode !== 200) {
                return [
                    'success' => false,
                    'error' => "cPanel API request failed with status {$statusCode}",
                    'response' => $responseBody,
                ];
            }

            // cPanel UAPI returns status: 1 for success, 0 for failure
            $success = ($responseBody['status'] ?? 0) === 1;

            if (!$success) {
                $errors = $responseBody['errors'] ?? [];
                $errorMessage = !empty($errors) 
                    ? implode(', ', array_column($errors, 'message'))
                    : ($responseBody['message'] ?? 'Unknown error');

                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'response' => $responseBody,
                ];
            }

            return [
                'success' => true,
                'data' => $responseBody['data'] ?? $responseBody,
                'response' => $responseBody,
            ];
        } catch (\Exception $e) {
            Log::error('cPanel API request failed', [
                'module' => $module,
                'function' => $function,
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
     * Add an addon domain in cPanel
     */
    public function addAddonDomain(string $domain, string $subdomain = null, string $documentRoot = null): array
    {
        // If subdomain not provided, use the domain without TLD as subdomain
        if (!$subdomain) {
            $subdomain = explode('.', $domain)[0];
        }

        // If document root not provided, use default public_html path
        if (!$documentRoot) {
            $documentRoot = "/home/{$this->username}/public_html/{$subdomain}";
        }

        $params = [
            'domain' => $domain,
            'subdomain' => $subdomain,
            'dir' => $documentRoot,
        ];

        Log::info('Adding addon domain in cPanel', $params);

        return $this->makeRequest('AddonDomain', 'add_addon_domain', $params);
    }

    /**
     * Add a parked domain in cPanel
     */
    public function addParkedDomain(string $domain): array
    {
        $params = [
            'domain' => $domain,
        ];

        Log::info('Adding parked domain in cPanel', $params);

        // cPanel UAPI: Park::park
        return $this->makeRequest('Park', 'park', $params);
    }

    /**
     * Check if a domain exists in cPanel
     */
    public function domainExists(string $domain): array
    {
        // Check addon domains
        // cPanel UAPI: AddonDomain::list_addon_domains returns array of domain objects
        $addonResult = $this->makeRequest('AddonDomain', 'list_addon_domains');
        
        if ($addonResult['success']) {
            $addonDomains = $addonResult['data'] ?? [];
            // Handle both array of objects and array of strings
            foreach ($addonDomains as $addonDomain) {
                $domainName = is_array($addonDomain) ? ($addonDomain['domain'] ?? $addonDomain['addondomain'] ?? null) : $addonDomain;
                if ($domainName === $domain) {
                    return [
                        'success' => true,
                        'exists' => true,
                        'type' => 'addon',
                        'data' => $addonDomain,
                    ];
                }
            }
        }

        // Check parked domains
        // cPanel UAPI: Park::list_parked_domains returns array of domain strings
        $parkedResult = $this->makeRequest('Park', 'list_parked_domains');
        
        if ($parkedResult['success']) {
            $parkedDomains = $parkedResult['data'] ?? [];
            // Handle both array of strings and array of objects
            foreach ($parkedDomains as $parkedDomain) {
                $domainName = is_array($parkedDomain) ? ($parkedDomain['domain'] ?? null) : $parkedDomain;
                if ($domainName === $domain) {
                    return [
                        'success' => true,
                        'exists' => true,
                        'type' => 'parked',
                        'data' => $parkedDomain,
                    ];
                }
            }
        }

        return [
            'success' => true,
            'exists' => false,
        ];
    }

    /**
     * Get the main domain's document root
     */
    public function getMainDomainDocumentRoot(): ?string
    {
        try {
            // cPanel UAPI: DomainInfo::list_domains
            $result = $this->makeRequest('DomainInfo', 'list_domains');
            
            if ($result['success']) {
                $data = $result['data'] ?? [];
                $mainDomain = $data['main_domain'] ?? null;
                
                if ($mainDomain) {
                    // Typically: /home/username/public_html
                    return "/home/{$this->username}/public_html";
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to get main domain document root', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Add shield domain to cPanel (as parked domain pointing to Laravel public directory)
     */
    public function addShieldDomain(string $domain, string $laravelPublicPath = null): array
    {
        // Check if domain already exists
        $existsResult = $this->domainExists($domain);
        
        if ($existsResult['success'] && ($existsResult['exists'] ?? false)) {
            return [
                'success' => true,
                'exists' => true,
                'type' => $existsResult['type'] ?? 'unknown',
                'message' => "Domain {$domain} already exists in cPanel as {$existsResult['type']} domain",
            ];
        }

        // If Laravel public path not provided, try to determine it
        if (!$laravelPublicPath) {
            // Try to get from main domain's document root
            $mainRoot = $this->getMainDomainDocumentRoot();
            if ($mainRoot) {
                // Assume Laravel is in a subdirectory (e.g., /public_html/main/public)
                // This might need adjustment based on your setup
                $laravelPublicPath = $mainRoot . '/public';
            } else {
                // Fallback: use default path
                $laravelPublicPath = "/home/{$this->username}/public_html/public";
            }
        }

        // Add as parked domain (uses same document root as main domain)
        // This is simpler and works well for shield domains
        $result = $this->addParkedDomain($domain);

        if ($result['success']) {
            Log::info('Shield domain added to cPanel successfully', [
                'domain' => $domain,
                'document_root' => $laravelPublicPath,
            ]);
        }

        return $result;
    }
}

