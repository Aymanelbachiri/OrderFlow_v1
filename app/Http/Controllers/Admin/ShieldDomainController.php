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
     * Verify DNS configuration for a shield domain
     */
    public function verifyDNS(ShieldDomain $shieldDomain)
    {
        try {
            // Check if Cloudflare is configured
            if (!$this->cloudflareService->isConfigured()) {
                return back()->withErrors(['error' => 'Cloudflare is not configured. Please configure it in Settings.']);
            }

            // If zone doesn't exist, create it first
            if (!$shieldDomain->cloudflare_zone_id) {
                // Create Cloudflare zone
                $zoneResult = $this->cloudflareService->addZone($shieldDomain->domain);
                
                if (!$zoneResult['success']) {
                    return back()->withErrors(['error' => 'Failed to create Cloudflare zone: ' . ($zoneResult['error'] ?? 'Unknown error')]);
                }

                // Get nameservers
                $nameservers = $zoneResult['nameservers'] ?? [];
                
                // Get Pages project
                $projectResult = $this->cloudflareService->getPagesProject();
                $pagesProjectId = $projectResult['success'] ? $projectResult['project_id'] : null;

                // Update shield domain with zone info
                $shieldDomain->update([
                    'cloudflare_zone_id' => $zoneResult['zone_id'],
                    'cloudflare_pages_project_id' => $pagesProjectId,
                    'cloudflare_nameservers' => $nameservers,
                ]);

                // Create Pages custom domain binding
                if ($pagesProjectId) {
                    $pagesResult = $this->cloudflareService->createPagesCustomDomain(
                        $shieldDomain->domain,
                        $zoneResult['zone_id']
                    );
                    
                    if (!$pagesResult['success']) {
                        Log::warning('Failed to create Pages custom domain', [
                            'domain' => $shieldDomain->domain,
                            'error' => $pagesResult['error'] ?? 'Unknown error',
                        ]);
                    }
                }

                return back()->with('success', 'Cloudflare zone created! Nameservers: ' . implode(', ', $nameservers) . '. Please configure these at your registrar, then click "Check Status" again to verify.');
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
                return back()->with('success', 'DNS is configured correctly! Nameservers are set at your registrar (' . $foundNs . '). Shield domain is now active.');
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
