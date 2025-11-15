<?php

namespace App\Services\Cloudflare;

use Illuminate\Support\Facades\Log;

/**
 * Cloudflare DNS record management service
 */
class DNSService
{
    public function __construct(
        private CloudflareApiClient $client
    ) {}

    /**
     * Get DNS records for a zone
     */
    public function getRecords(string $zoneId, ?string $type = null, ?string $name = null): array
    {
        $params = array_filter([
            'type' => $type,
            'name' => $name,
        ]);

        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        return $this->client->request('GET', "/zones/{$zoneId}/dns_records{$queryString}");
    }

    /**
     * Create a DNS record
     */
    public function createRecord(
        string $zoneId,
        string $type,
        string $name,
        string $content,
        int $ttl = 3600,
        bool $proxied = true
    ): array {
        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
        ];

        // Proxied records have auto TTL
        if (in_array($type, ['A', 'CNAME']) && $proxied) {
            $data['proxied'] = true;
        } else {
            $data['ttl'] = $ttl;
        }

        return $this->client->request('POST', "/zones/{$zoneId}/dns_records", $data);
    }

    /**
     * Delete a DNS record
     */
    public function deleteRecord(string $zoneId, string $recordId): array
    {
        return $this->client->request('DELETE', "/zones/{$zoneId}/dns_records/{$recordId}");
    }

    /**
     * Create DNS records for shield domain pointing to main server
     */
    public function createShieldDomainRecords(string $domain, string $zoneId): array
    {
        $appUrl = config('app.url', env('APP_URL', 'http://localhost'));
        $targetHost = parse_url($appUrl, PHP_URL_HOST);
        
        if (!$targetHost) {
            return [
                'success' => false,
                'error' => 'Could not determine main server hostname from APP_URL',
            ];
        }

        // Get existing records
        $existingResult = $this->getRecords($zoneId);
        if (!$existingResult['success']) {
            return [
                'success' => false,
                'error' => 'Failed to fetch existing DNS records: ' . ($existingResult['error'] ?? 'Unknown error'),
            ];
        }

        $existingRecords = $existingResult['data']['result'] ?? [];
        $hasRoot = false;
        $hasWWW = false;

        // Check for existing records
        foreach ($existingRecords as $record) {
            $name = rtrim($record['name'], '.');
            $content = $record['content'] ?? '';
            
            if (($name === $domain || $name === '@') && $content === $targetHost) {
                $hasRoot = true;
            }
            if (($name === 'www.' . $domain || $name === 'www') && $content === $targetHost) {
                $hasWWW = true;
            }
        }

        $created = [];
        $failed = [];

        // Create root domain CNAME
        if (!$hasRoot) {
            $result = $this->createRecord($zoneId, 'CNAME', $domain, $targetHost, 3600, true);
            if ($result['success']) {
                $created[] = 'root';
            } else {
                $failed[] = 'root';
            }
        }

        // Create www subdomain CNAME
        if (!$hasWWW) {
            $result = $this->createRecord($zoneId, 'CNAME', 'www.' . $domain, $targetHost, 3600, true);
            if ($result['success']) {
                $created[] = 'www';
            } else {
                $failed[] = 'www';
            }
        }

        return [
            'success' => count($created) > 0 || (count($failed) === 0 && ($hasRoot || $hasWWW)),
            'domain' => $domain,
            'zone_id' => $zoneId,
            'target_host' => $targetHost,
            'created_records' => $created,
            'failed_records' => $failed,
            'root_existed' => $hasRoot,
            'www_existed' => $hasWWW,
            'message' => count($created) > 0 
                ? 'Created ' . count($created) . ' DNS record(s)'
                : 'All DNS records already exist',
        ];
    }

    /**
     * Delete shield domain DNS records
     */
    public function deleteShieldDomainRecords(string $domain, string $zoneId): array
    {
        $appUrl = config('app.url', env('APP_URL', 'http://localhost'));
        $targetHost = parse_url($appUrl, PHP_URL_HOST);
        
        if (!$targetHost) {
            return [
                'success' => false,
                'error' => 'Could not determine main server hostname from APP_URL',
            ];
        }

        $recordsResult = $this->getRecords($zoneId);
        if (!$recordsResult['success']) {
            return [
                'success' => false,
                'error' => 'Failed to fetch DNS records: ' . ($recordsResult['error'] ?? 'Unknown error'),
            ];
        }

        $records = $recordsResult['data']['result'] ?? [];
        $deleted = [];
        $failed = [];

        foreach ($records as $record) {
            $name = rtrim($record['name'], '.');
            $content = $record['content'] ?? '';
            $type = $record['type'] ?? '';
            $id = $record['id'] ?? '';

            $isRoot = ($name === $domain || $name === '@') 
                && $type === 'CNAME' 
                && $content === $targetHost;
            
            $isWWW = ($name === 'www.' . $domain || $name === 'www') 
                && $type === 'CNAME' 
                && $content === $targetHost;

            if (($isRoot || $isWWW) && $id) {
                $result = $this->deleteRecord($zoneId, $id);
                if ($result['success']) {
                    $deleted[] = $isRoot ? 'root' : 'www';
                } else {
                    $failed[] = $isRoot ? 'root' : 'www';
                }
            }
        }

        return [
            'success' => count($deleted) > 0 || count($failed) === 0,
            'domain' => $domain,
            'zone_id' => $zoneId,
            'deleted_records' => $deleted,
            'failed_records' => $failed,
            'message' => count($deleted) > 0 
                ? 'Deleted ' . count($deleted) . ' DNS record(s)'
                : (count($failed) > 0 ? 'Failed to delete records' : 'No matching records found'),
        ];
    }
}

