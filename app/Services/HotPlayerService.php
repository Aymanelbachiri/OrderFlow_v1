<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Source;

class HotPlayerService
{
    protected string $baseUrl = 'https://hotplayer.app/api/v1/reseller';
    protected ?string $apiKey;

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    public static function forSource(string $sourceName): self
    {
        $source = Source::where('name', $sourceName)->first();
        $apiKey = $source?->hotplayer_api_key;
        
        return new self($apiKey);
    }

    public function checkDevice(string $mac): array
    {
        if (!$this->apiKey) {
            return [
                'success' => false,
                'error' => 'HotPlayer API key not configured',
            ];
        }

        // Normalize MAC address format
        $mac = $this->normalizeMac($mac);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Content-type' => 'application/json; charset=UTF-8',
                    'Authorization' => 'ApiKey ' . $this->apiKey,
                ])
                ->get("{$this->baseUrl}/check-device/{$mac}");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['status'] ?? 'unknown',
                    'mac' => $data['mac'] ?? $mac,
                    'expiration' => $data['expiration'] ?? null,
                    'plan' => $data['plan'] ?? null,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'Device not found or invalid MAC address',
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to connect to HotPlayer API: ' . $e->getMessage(),
            ];
        }
    }

    protected function normalizeMac(string $mac): string
    {
        // Remove any separators and convert to uppercase
        $mac = strtoupper(preg_replace('/[^A-Fa-f0-9]/', '', $mac));
        
        // Format as XX:XX:XX:XX:XX:XX
        if (strlen($mac) === 12) {
            return implode(':', str_split($mac, 2));
        }
        
        return $mac;
    }

    public static function isValidMacFormat(string $mac): bool
    {
        // Accept various MAC formats (alphanumeric, not just hex)
        $patterns = [
            '/^([0-9A-Za-z]{2}:){5}[0-9A-Za-z]{2}$/',  // XX:XX:XX:XX:XX:XX
            '/^([0-9A-Za-z]{2}-){5}[0-9A-Za-z]{2}$/',  // XX-XX-XX-XX-XX-XX
            '/^[0-9A-Za-z]{12}$/',                      // XXXXXXXXXXXX
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $mac)) {
                return true;
            }
        }

        return false;
    }
}
