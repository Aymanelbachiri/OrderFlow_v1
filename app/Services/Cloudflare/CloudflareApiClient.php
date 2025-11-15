<?php

namespace App\Services\Cloudflare;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Core Cloudflare API client
 * Handles authentication and HTTP communication
 */
class CloudflareApiClient
{
    private string $apiToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiToken = \App\Models\SystemSetting::get('cloudflare_api_token') 
            ?: config('services.cloudflare.api_token', '');
        $this->baseUrl = config('services.cloudflare.api_base_url', 'https://api.cloudflare.com/client/v4');
    }

    /**
     * Make authenticated API request
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        if (empty($this->apiToken)) {
            return [
                'success' => false,
                'error' => 'Cloudflare API token is not configured.',
            ];
        }

        $url = $this->baseUrl . $endpoint;
        $headers = ['Authorization' => 'Bearer ' . $this->apiToken];
        
        // Only add Content-Type for requests with body
        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($data)) {
            $headers['Content-Type'] = 'application/json';
        }

        $httpClient = Http::withHeaders($headers)->withOptions($this->getHttpOptions());

        try {
            $response = match(strtoupper($method)) {
                'GET' => $httpClient->get($url, $data),
                'POST' => $httpClient->post($url, $data),
                'PUT' => $httpClient->put($url, $data),
                'DELETE' => $httpClient->delete($url, $data),
                default => $httpClient->{strtolower($method)}($url, $data),
            };

            $statusCode = $response->status();
            $responseBody = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseBody,
                ];
            }

            // Handle authentication errors
            if (in_array($statusCode, [401, 403])) {
                $error = $responseBody['errors'][0]['message'] ?? 'Authentication failed';
                return [
                    'success' => false,
                    'error' => "Authentication error: {$error}",
                    'status_code' => $statusCode,
                ];
            }

            // Handle other errors
            $error = $responseBody['errors'][0]['message'] ?? 'Unknown error';
            return [
                'success' => false,
                'error' => $error,
                'errors' => $responseBody['errors'] ?? [],
                'status_code' => $statusCode,
            ];

        } catch (\Exception $e) {
            Log::error('Cloudflare API Exception', [
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
     * Get HTTP client options with SSL configuration
     */
    private function getHttpOptions(): array
    {
        $options = ['timeout' => 30, 'connect_timeout' => 10];

        if (app()->environment(['local', 'development'])) {
            $options['verify'] = false;
        } else {
            // Try to find CA bundle
            $caBundles = [
                '/etc/ssl/certs/ca-certificates.crt',
                '/etc/pki/tls/certs/ca-bundle.crt',
                '/usr/local/etc/openssl/cert.pem',
                base_path('vendor/composer/ca-bundle/ca-bundle.crt'),
            ];

            foreach ($caBundles as $caPath) {
                if (file_exists($caPath)) {
                    $options['verify'] = $caPath;
                    return $options;
                }
            }

            $options['verify'] = true;
        }

        return $options;
    }

    /**
     * Check if API is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiToken);
    }
}

