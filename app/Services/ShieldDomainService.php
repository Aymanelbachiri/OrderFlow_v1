<?php

namespace App\Services;

use App\Models\ShieldDomain;
use App\Services\Cloudflare\CloudflareApiClient;
use App\Services\Cloudflare\ZoneService;
use App\Services\Cloudflare\DNSService;
use Illuminate\Support\Facades\Log;

/**
 * Shield Domain business logic service
 * Orchestrates Cloudflare operations and domain management
 */
class ShieldDomainService
{
    public function __construct(
        private CloudflareApiClient $apiClient,
        private ZoneService $zoneService,
        private DNSService $dnsService,
        private ?CPanelService $cpanelService = null
    ) {
        $this->cpanelService = $this->cpanelService ?? new CPanelService();
    }

    /**
     * Create Cloudflare zone for shield domain
     */
    public function createZone(ShieldDomain $shieldDomain): array
    {
        if (!$this->apiClient->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Cloudflare is not configured. Please configure it in Settings.',
            ];
        }

        if ($shieldDomain->cloudflare_zone_id) {
            return [
                'success' => true,
                'zone_id' => $shieldDomain->cloudflare_zone_id,
                'nameservers' => $shieldDomain->cloudflare_nameservers ?? [],
                'existing' => true,
                'message' => 'Zone already exists',
            ];
        }

        // Create or get zone
        $result = $this->zoneService->createOrGet($shieldDomain->domain);
        
        if (!$result['success']) {
            return $result;
        }

        // Update shield domain
        $shieldDomain->update([
            'cloudflare_zone_id' => $result['zone_id'],
            'cloudflare_nameservers' => $result['nameservers'],
        ]);

        // Trigger DNS scan
        $this->zoneService->triggerDNSScan($result['zone_id']);

        // Add to cPanel if configured
        if ($this->cpanelService->isConfigured()) {
            try {
                $this->cpanelService->addShieldDomain($shieldDomain->domain);
            } catch (\Exception $e) {
                Log::warning('cPanel automation failed', [
                    'domain' => $shieldDomain->domain,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'success' => true,
            'zone_id' => $result['zone_id'],
            'nameservers' => $result['nameservers'],
            'existing' => $result['existing'] ?? false,
            'message' => $result['existing'] 
                ? 'Zone already exists and has been linked'
                : 'Zone created successfully',
        ];
    }

    /**
     * Verify DNS nameservers
     */
    public function verifyDNS(ShieldDomain $shieldDomain): array
    {
        if (!$shieldDomain->cloudflare_zone_id) {
            return [
                'success' => false,
                'error' => 'Cloudflare zone not found. Please create the zone first.',
            ];
        }

        $result = $this->zoneService->verifyNameservers($shieldDomain->domain);
        
        if ($result['success'] && $result['configured']) {
            $shieldDomain->update([
                'dns_configured' => true,
                'dns_configured_at' => now(),
                'status' => 'active',
            ]);
        } else {
            $shieldDomain->update([
                'dns_configured' => false,
                'status' => 'pending',
            ]);
        }

        return $result;
    }

    /**
     * Configure DNS records pointing to main server
     */
    public function configureDNS(ShieldDomain $shieldDomain): array
    {
        if (!$shieldDomain->cloudflare_zone_id) {
            return [
                'success' => false,
                'error' => 'Cloudflare zone not found. Please create the zone first.',
            ];
        }

        return $this->dnsService->createShieldDomainRecords(
            $shieldDomain->domain,
            $shieldDomain->cloudflare_zone_id
        );
    }

    /**
     * Delete DNS records
     */
    public function deleteDNSRecords(ShieldDomain $shieldDomain): array
    {
        if (!$shieldDomain->cloudflare_zone_id) {
            return [
                'success' => false,
                'error' => 'Cloudflare zone not found.',
            ];
        }

        return $this->dnsService->deleteShieldDomainRecords(
            $shieldDomain->domain,
            $shieldDomain->cloudflare_zone_id
        );
    }

    /**
     * Delete Cloudflare zone
     */
    public function deleteZone(ShieldDomain $shieldDomain): array
    {
        if (!$shieldDomain->cloudflare_zone_id) {
            return [
                'success' => false,
                'error' => 'No Cloudflare zone found.',
            ];
        }

        $result = $this->zoneService->delete($shieldDomain->cloudflare_zone_id);
        
        if ($result['success']) {
            $shieldDomain->update([
                'cloudflare_zone_id' => null,
                'cloudflare_nameservers' => null,
                'dns_configured' => false,
                'dns_configured_at' => null,
                'status' => 'pending',
            ]);
        }

        return $result;
    }

    /**
     * Sync nameservers from Cloudflare
     */
    public function syncNameservers(ShieldDomain $shieldDomain): array
    {
        if (!$shieldDomain->cloudflare_zone_id) {
            return [
                'success' => false,
                'error' => 'Cloudflare zone not found.',
            ];
        }

        $result = $this->zoneService->getNameservers($shieldDomain->cloudflare_zone_id);
        
        if ($result['success']) {
            $shieldDomain->update([
                'cloudflare_nameservers' => $result['nameservers'],
            ]);
        }

        return $result;
    }
}

