<?php

namespace App\Services\Cloudflare;

use Illuminate\Support\Facades\Log;

/**
 * Cloudflare Zone management service
 */
class ZoneService
{
    public function __construct(
        private CloudflareApiClient $client
    ) {}

    /**
     * Get account ID from settings
     */
    private function getAccountId(): string
    {
        return \App\Models\SystemSetting::get('cloudflare_account_id') 
            ?: config('services.cloudflare.account_id', '');
    }

    /**
     * Get zone by domain name
     */
    public function getByDomain(string $domain): array
    {
        $result = $this->client->request('GET', '/zones', ['name' => $domain]);

        if (!$result['success']) {
            return $result;
        }

        $zones = $result['data']['result'] ?? [];
        if (empty($zones)) {
            return [
                'success' => false,
                'error' => 'Zone not found',
            ];
        }

        $zone = $zones[0];
        return [
            'success' => true,
            'zone_id' => $zone['id'],
            'nameservers' => $zone['name_servers'] ?? [],
            'zone' => $zone,
        ];
    }

    /**
     * Create or get existing zone
     */
    public function createOrGet(string $domain): array
    {
        // Check if zone already exists
        $existing = $this->getByDomain($domain);
        if ($existing['success']) {
            return [
                'success' => true,
                'zone_id' => $existing['zone_id'],
                'nameservers' => $existing['nameservers'],
                'existing' => true,
            ];
        }

        // Create new zone
        $result = $this->client->request('POST', '/zones', [
            'name' => $domain,
            'account' => ['id' => $this->getAccountId()],
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'zone_id' => $result['data']['result']['id'],
                'nameservers' => $result['data']['result']['name_servers'] ?? [],
                'existing' => false,
            ];
        }

        // If error says zone exists, try getting it again
        $error = $result['error'] ?? '';
        if (stripos($error, 'already exists') !== false || stripos($error, 'duplicate') !== false) {
            $existing = $this->getByDomain($domain);
            if ($existing['success']) {
                return [
                    'success' => true,
                    'zone_id' => $existing['zone_id'],
                    'nameservers' => $existing['nameservers'],
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
        $result = $this->client->request('GET', "/zones/{$zoneId}");

        if (!$result['success']) {
            return $result;
        }

        return [
            'success' => true,
            'nameservers' => $result['data']['result']['name_servers'] ?? [],
        ];
    }

    /**
     * Delete a zone
     */
    public function delete(string $zoneId): array
    {
        return $this->client->request('DELETE', "/zones/{$zoneId}");
    }

    /**
     * Trigger DNS scan to import existing records
     */
    public function triggerDNSScan(string $zoneId): array
    {
        return $this->client->request('POST', "/zones/{$zoneId}/dns_records/scan/trigger");
    }

    /**
     * Verify DNS nameservers are configured
     */
    public function verifyNameservers(string $domain): array
    {
        try {
            $nameservers = dns_get_record($domain, DNS_NS);
            
            if (empty($nameservers)) {
                return [
                    'success' => false,
                    'configured' => false,
                    'message' => 'No nameservers found',
                ];
            }

            // Get zone to compare nameservers
            $zoneResult = $this->getByDomain($domain);
            if (!$zoneResult['success']) {
                return [
                    'success' => false,
                    'configured' => false,
                    'message' => 'Zone not found in Cloudflare',
                ];
            }

            $expectedNameservers = $zoneResult['nameservers'] ?? [];
            $foundNameservers = array_unique(array_map(
                fn($ns) => strtolower($ns['target'] ?? ''),
                $nameservers
            ));

            $expectedLower = array_map('strtolower', $expectedNameservers);
            $exactMatch = !empty(array_intersect($expectedLower, $foundNameservers));

            // Check if all are Cloudflare nameservers
            $allCloudflare = true;
            foreach ($foundNameservers as $ns) {
                if (!str_ends_with($ns, '.ns.cloudflare.com')) {
                    $allCloudflare = false;
                    break;
                }
            }

            $configured = $exactMatch || ($allCloudflare && count($foundNameservers) >= 2);

            return [
                'success' => true,
                'configured' => $configured,
                'expected_nameservers' => $expectedNameservers,
                'found_nameservers' => array_values($foundNameservers),
                'exact_match' => $exactMatch,
                'all_cloudflare_ns' => $allCloudflare,
                'message' => $configured 
                    ? ($exactMatch ? 'Nameservers correctly configured' : 'Cloudflare nameservers detected')
                    : 'Nameservers not configured correctly',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'configured' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

