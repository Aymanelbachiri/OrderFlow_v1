<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShieldDomain;
use App\Services\CloudflareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShieldDomainController extends Controller
{
    protected CloudflareService $cloudflareService;

    public function __construct(CloudflareService $cloudflareService)
    {
        $this->cloudflareService = $cloudflareService;
    }

    public function index(Request $request)
    {
        $query = ShieldDomain::withCount('sources');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('domain', 'like', "%{$search}%")
                  ->orWhere('template_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shieldDomains = $query->latest()->paginate(20);
        return view('admin.shield-domains.index', compact('shieldDomains'));
    }

    public function create()
    {
        $templates = ['template-1', 'template-2', 'template-3'];
        return view('admin.shield-domains.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:shield_domains,domain',
            'template_name' => 'required|string|in:template-1,template-2,template-3',
        ]);

        try {
            // Create shield domain record without Cloudflare setup
            // User will configure nameservers first, then click "Check Status" to complete setup
            $shieldDomain = ShieldDomain::create([
                'domain' => $validated['domain'],
                'template_name' => $validated['template_name'],
                'status' => 'pending',
                'cloudflare_zone_id' => null,
                'cloudflare_pages_project_id' => null,
                'cloudflare_nameservers' => null,
                'dns_configured' => false,
            ]);

            return redirect()->route('admin.shield-domains.edit', $shieldDomain)
                ->with('success', 'Shield domain created! Please configure nameservers at your registrar, then click "Check Status" to complete the setup.');
        } catch (\Exception $e) {
            Log::error('Failed to create shield domain', [
                'domain' => $validated['domain'],
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->withErrors(['domain' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function show(ShieldDomain $shieldDomain)
    {
        $shieldDomain->loadCount('sources');
        $shieldDomain->load('sources');
        return view('admin.shield-domains.show', compact('shieldDomain'));
    }

    public function edit(ShieldDomain $shieldDomain)
    {
        // Refresh to ensure we have the latest data
        $shieldDomain->refresh();
        
        $templates = ['template-1', 'template-2', 'template-3'];
        return view('admin.shield-domains.edit', compact('shieldDomain', 'templates'));
    }

    public function update(Request $request, ShieldDomain $shieldDomain)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:shield_domains,domain,' . $shieldDomain->id,
            'template_name' => 'required|string|in:template-1,template-2,template-3',
        ]);

        $shieldDomain->update($validated);

        return redirect()->route('admin.shield-domains.index')
            ->with('success', 'Shield domain updated successfully.');
    }

    public function destroy(ShieldDomain $shieldDomain)
    {
        try {
            // Delete from Cloudflare if zone ID exists
            if ($shieldDomain->cloudflare_zone_id) {
                $this->cloudflareService->deleteZone($shieldDomain->cloudflare_zone_id);
            }

            $shieldDomain->delete();

            return redirect()->route('admin.shield-domains.index')
                ->with('success', 'Shield domain deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete shield domain', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['error' => 'Failed to delete shield domain: ' . $e->getMessage()]);
        }
    }

    /**
     * Create Cloudflare zone for a shield domain
     */
    public function createZone(ShieldDomain $shieldDomain)
    {
        try {
            // Check if Cloudflare is configured
            if (!$this->cloudflareService->isConfigured()) {
                return back()->withErrors(['error' => 'Cloudflare is not configured. Please configure it in Settings.']);
            }

            // Check if zone already exists
            if ($shieldDomain->cloudflare_zone_id) {
                return back()->with('info', 'Cloudflare zone already exists. Zone ID: ' . $shieldDomain->cloudflare_zone_id);
            }

            // Create Cloudflare zone or get existing one
            $zoneResult = $this->cloudflareService->addZone($shieldDomain->domain);
            
            if (!$zoneResult['success']) {
                return back()->withErrors(['error' => 'Failed to create/get Cloudflare zone: ' . ($zoneResult['error'] ?? 'Unknown error')]);
            }

            // Get nameservers
            $nameservers = $zoneResult['nameservers'] ?? [];

            // Update shield domain with zone info
            $shieldDomain->update([
                'cloudflare_zone_id' => $zoneResult['zone_id'],
                'cloudflare_nameservers' => $nameservers,
            ]);

            // Trigger DNS scan to import existing records
            $scanResult = $this->cloudflareService->triggerDNSScan($zoneResult['zone_id']);
            Log::info('DNS scan triggered for zone', [
                'domain' => $shieldDomain->domain,
                'zone_id' => $zoneResult['zone_id'],
                'existing_zone' => $zoneResult['existing'] ?? false,
            ]);

            $message = ($zoneResult['existing'] ?? false) 
                ? 'Cloudflare zone already exists and has been linked! Nameservers: ' . implode(', ', $nameservers) . '. Please configure these at your registrar.'
                : 'Cloudflare zone created successfully! Nameservers: ' . implode(', ', $nameservers) . '. Please configure these at your registrar.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Zone creation failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Zone creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Check DNS nameserver status only
     */
    public function verifyDNS(ShieldDomain $shieldDomain)
    {
        try {
            // Check if Cloudflare is configured
            if (!$this->cloudflareService->isConfigured()) {
                return back()->withErrors(['error' => 'Cloudflare is not configured. Please configure it in Settings.']);
            }

            // Check if zone exists
            if (!$shieldDomain->cloudflare_zone_id) {
                return back()->withErrors(['error' => 'Cloudflare zone not found. Please create the zone first by clicking "Create Zone".']);
            }

            // Verify DNS is configured by checking if nameservers are set at registrar
            $result = $this->cloudflareService->verifyDNS($shieldDomain->domain);

            if (!$result['success']) {
                return back()->with('warning', 'Could not verify DNS: ' . ($result['message'] ?? 'Unknown error'));
            }

            if ($result['configured']) {
                // Nameservers are correctly configured
                $shieldDomain->update([
                    'dns_configured' => true,
                    'dns_configured_at' => now(),
                    'status' => 'active',
                ]);

                $foundNs = implode(', ', $result['found_nameservers'] ?? []);
                return back()->with('success', 'DNS nameservers are configured correctly! Found at registrar: ' . $foundNs . '. Shield domain is now active.');
            } else {
                // Nameservers are not configured yet
                $shieldDomain->update([
                    'dns_configured' => false,
                    'status' => 'pending',
                ]);

                $expectedNs = implode(', ', $result['expected_nameservers'] ?? $shieldDomain->cloudflare_nameservers ?? []);
                $foundNs = !empty($result['found_nameservers']) ? implode(', ', $result['found_nameservers']) : 'None found';
                
                $message = 'DNS nameservers are not configured yet. ';
                $message .= 'Expected: ' . $expectedNs . '. ';
                $message .= 'Found at registrar: ' . $foundNs . '. ';
                $message .= 'Please update your nameservers at your domain registrar.';
                
                return back()->with('warning', $message);
            }
        } catch (\Exception $e) {
            Log::error('DNS verification failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'DNS verification failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Manually configure DNS records for shield domain
     */
    public function configureDNS(ShieldDomain $shieldDomain)
    {
        try {
            if (!$shieldDomain->cloudflare_zone_id) {
                return back()->withErrors(['error' => 'No Cloudflare zone ID found. Please create the zone first by clicking "Check Status".']);
            }
            
            $dnsResult = $this->cloudflareService->createShieldDomainDNSRecords(
                $shieldDomain->domain,
                $shieldDomain->cloudflare_zone_id
            );

            if ($dnsResult['success']) {
                $message = 'DNS records configuration completed. ';
                $createdCount = count($dnsResult['created_records'] ?? []);
                $failedCount = count($dnsResult['failed_records'] ?? []);
                $targetHost = $dnsResult['target_host'] ?? 'main server';
                if ($dnsResult['root_record_created'] ?? false) { $message .= 'Root domain CNAME created pointing to ' . $targetHost . '. '; }
                if ($dnsResult['www_record_created'] ?? false) { $message .= 'WWW subdomain CNAME created pointing to ' . $targetHost . '. '; }
                if (($dnsResult['root_record_existed'] ?? false) && ($dnsResult['www_record_existed'] ?? false)) { $message .= 'DNS records already exist. '; }
                if ($failedCount > 0) { $message .= "Warning: {$failedCount} record(s) failed to create. Check logs for details. "; }
                if ($createdCount === 0 && $failedCount === 0) { $message .= 'No new records were created (all already exist). '; }
                $message .= 'Check Cloudflare dashboard to verify. Check Laravel logs for detailed information.';
                Log::info('DNS configuration completed', ['domain' => $shieldDomain->domain, 'result' => $dnsResult,]);
                return back()->with('success', $message);
            } else {
                $errorMsg = 'DNS configuration failed: ' . ($dnsResult['error'] ?? 'Unknown error');
                Log::error('DNS configuration failed', ['domain' => $shieldDomain->domain, 'result' => $dnsResult,]);
                return back()->withErrors(['error' => $errorMsg]);
            }
        } catch (\Exception $e) {
            Log::error('DNS configuration failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'DNS configuration failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Sync shield domain with Cloudflare
     */
    public function syncCloudflare(ShieldDomain $shieldDomain)
    {
        try {
            if (!$shieldDomain->cloudflare_zone_id) {
                return back()->withErrors(['error' => 'No Cloudflare zone ID found.']);
            }

            // Get updated nameservers
            $nameserversResult = $this->cloudflareService->getNameservers($shieldDomain->cloudflare_zone_id);

            if ($nameserversResult['success']) {
                $shieldDomain->update([
                    'cloudflare_nameservers' => $nameserversResult['nameservers'],
                ]);

                return back()->with('success', 'Shield domain synced with Cloudflare successfully.');
            }

            return back()->withErrors(['error' => 'Failed to sync: ' . ($nameserversResult['error'] ?? 'Unknown error')]);
        } catch (\Exception $e) {
            Log::error('Cloudflare sync failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Sync failed: ' . $e->getMessage()]);
        }
    }
}
