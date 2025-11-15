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
        // Get token from database settings first, then fall back to config/env
        $dbToken = \App\Models\SystemSetting::get('cloudflare_api_token');
        $configToken = config('services.cloudflare.api_token', '');
        
        $this->apiToken = !empty($dbToken) ? $dbToken : $configToken;
        $this->baseUrl = config('services.cloudflare.api_base_url', 'https://api.cloudflare.com/client/v4');
        
        // Log token length for debugging (first 4 chars only for security)
        if (!empty($this->apiToken)) {
            \Illuminate\Support\Facades\Log::debug('CloudflareApiClient initialized', [
                'token_length' => strlen($this->apiToken),
                'token_preview' => substr($this->apiToken, 0, 4) . '...',
                'source' => !empty($dbToken) ? 'database' : 'config',
            ]);
        }
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

        // Ensure token is properly formatted (trim whitespace)
        $token = trim($this->apiToken);
        if (empty($token) || strlen($token) < 20) {
            \Illuminate\Support\Facades\Log::error('Cloudflare API token appears invalid', [
                'token_length' => strlen($token),
                'token_preview' => substr($token, 0, 4) . '...',
            ]);
            return [
                'success' => false,
                'error' => 'Cloudflare API token is invalid or too short. Please check your settings.',
            ];
        }

        $url = $this->baseUrl . $endpoint;
        $headers = ['Authorization' => 'Bearer ' . $token];
        
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

