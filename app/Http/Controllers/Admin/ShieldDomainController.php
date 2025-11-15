<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShieldDomain;
use App\Services\ShieldDomainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShieldDomainController extends Controller
{
    public function __construct(
        private ShieldDomainService $shieldDomainService
    ) {}

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
            $shieldDomain = ShieldDomain::create([
                'domain' => $validated['domain'],
                'template_name' => $validated['template_name'],
                'status' => 'pending',
            ]);

            return redirect()->route('admin.shield-domains.show', $shieldDomain)
                ->with('success', 'Shield domain created! Click "Create Cloudflare Zone" to get started.');
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
        $shieldDomain->refresh();
        $shieldDomain->loadCount('sources');
        $shieldDomain->load('sources');
        return view('admin.shield-domains.show', compact('shieldDomain'));
    }

    public function edit(ShieldDomain $shieldDomain)
    {
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
            if ($shieldDomain->cloudflare_zone_id) {
                $this->shieldDomainService->deleteZone($shieldDomain);
            }

            $shieldDomain->delete();

            return redirect()->route('admin.shield-domains.index')
                ->with('success', 'Shield domain deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete shield domain', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to delete: ' . $e->getMessage()]);
        }
    }

    /**
     * Create Cloudflare zone
     */
    public function createZone(Request $request, ShieldDomain $shieldDomain)
    {
        try {
            $result = $this->shieldDomainService->createZone($shieldDomain);

            if (!$result['success']) {
                return back()->with('error', $result['error'])->withErrors(['error' => $result['error']]);
            }

            $shieldDomain->refresh();
            $nameservers = implode(', ', $result['nameservers'] ?? []);
            $message = $result['existing'] 
                ? "Zone already exists! Nameservers: {$nameservers}. Configure these at your registrar."
                : "Zone created! Nameservers: {$nameservers}. Configure these at your registrar.";

            return redirect()->route('admin.shield-domains.show', $shieldDomain)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Zone creation failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Zone creation failed: ' . $e->getMessage())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Verify DNS nameservers
     */
    public function verifyDNS(ShieldDomain $shieldDomain)
    {
        try {
            $result = $this->shieldDomainService->verifyDNS($shieldDomain);

            if (!$result['success']) {
                return back()->with('warning', $result['message'] ?? 'DNS verification failed');
            }

            $shieldDomain->refresh();

            if ($result['configured']) {
                $found = implode(', ', $result['found_nameservers'] ?? []);
                return back()->with('success', "DNS configured correctly! Found: {$found}");
            } else {
                $expected = implode(', ', $result['expected_nameservers'] ?? []);
                $found = implode(', ', $result['found_nameservers'] ?? []);
                return back()->with('warning', "DNS not configured. Expected: {$expected}. Found: {$found}");
            }
        } catch (\Exception $e) {
            Log::error('DNS verification failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'DNS verification failed: ' . $e->getMessage())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Configure DNS records
     */
    public function configureDNS(ShieldDomain $shieldDomain)
    {
        try {
            $result = $this->shieldDomainService->configureDNS($shieldDomain);

            if (!$result['success']) {
                return back()->with('error', $result['error'])->withErrors(['error' => $result['error']]);
            }

            $created = count($result['created_records'] ?? []);
            $message = $result['message'] ?? "DNS configuration completed. Created {$created} record(s).";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('DNS configuration failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'DNS configuration failed: ' . $e->getMessage())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete DNS records
     */
    public function deleteDNSRecords(ShieldDomain $shieldDomain)
    {
        try {
            $result = $this->shieldDomainService->deleteDNSRecords($shieldDomain);

            if (!$result['success']) {
                return back()->with('error', $result['error'])->withErrors(['error' => $result['error']]);
            }

            $deleted = count($result['deleted_records'] ?? []);
            $message = $result['message'] ?? "Deleted {$deleted} DNS record(s).";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('DNS deletion failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'DNS deletion failed: ' . $e->getMessage())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete Cloudflare zone
     */
    public function deleteZone(ShieldDomain $shieldDomain)
    {
        try {
            $result = $this->shieldDomainService->deleteZone($shieldDomain);

            if (!$result['success']) {
                return back()->with('error', $result['error'])->withErrors(['error' => $result['error']]);
            }

            $shieldDomain->refresh();
            return back()->with('success', 'Cloudflare zone deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Zone deletion failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Zone deletion failed: ' . $e->getMessage())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Sync with Cloudflare
     */
    public function syncCloudflare(ShieldDomain $shieldDomain)
    {
        try {
            $result = $this->shieldDomainService->syncNameservers($shieldDomain);

            if (!$result['success']) {
                return back()->with('error', $result['error'])->withErrors(['error' => $result['error']]);
            }

            $shieldDomain->refresh();
            return back()->with('success', 'Shield domain synced with Cloudflare successfully.');
        } catch (\Exception $e) {
            Log::error('Cloudflare sync failed', [
                'domain' => $shieldDomain->domain,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Sync failed: ' . $e->getMessage())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
