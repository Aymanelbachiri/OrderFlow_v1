<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Traits\AdminScopesData;

class SourceController extends Controller
{
    use AdminScopesData;

    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_sources')) {
            abort(403, 'You do not have permission to manage sources.');
        }

        // Handle case when migration hasn't been run yet
        if (!Schema::hasTable('sources')) {
            $sources = collect();
            $schemaMissing = true;
            return view('admin.sources.index', compact('sources', 'schemaMissing'));
        }

        $query = Source::query();
        
        // Scope to admin's data (unless super admin)
        $this->scopeToAdmin($query);

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('return_url', 'like', "%{$search}%");
            });
        }

        $sources = $query->latest()->paginate(20);
        return view('admin.sources.index', compact('sources'));
    }

    public function create()
    {
        return view('admin.sources.create');
    }

    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_sources')) {
            abort(403, 'You do not have permission to manage sources.');
        }

        // Check limit
        $user = auth()->user();
        if (!$user->canCreateResource('sources')) {
            return redirect()->route('admin.sources.index')
                ->with('error', 'You have reached your maximum number of sources.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sources,name',
            'return_url' => 'required|url|max:2048',
            'is_active' => 'sometimes|boolean',
            // SMTP Config
            'smtp_mailer' => 'nullable|string|in:smtp,sendmail,mailgun,ses,postmark',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_encryption' => 'nullable|string|in:tls,ssl',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            // Email Variables
            'email_company_name' => 'nullable|string|max:255',
            'email_website_url' => 'nullable|url|max:2048',
            'email_support_email' => 'nullable|email|max:255',
            'email_contact_email' => 'nullable|email|max:255',
            'email_team_name' => 'nullable|string|max:255',
            'email_contact_phone' => 'nullable|string|max:50',
            'email_contact_address' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? true);
        $validated['admin_id'] = $this->getCurrentAdminId();

        // Build SMTP config
        $smtpConfig = null;
        if ($request->filled('smtp_host')) {
            $smtpConfig = [
                'mailer' => $request->input('smtp_mailer', 'smtp'),
                'host' => $request->input('smtp_host'),
                'port' => $request->input('smtp_port', 587),
                'encryption' => $request->input('smtp_encryption', 'tls'),
                'username' => $request->input('smtp_username'),
                'password' => $request->input('smtp_password'),
                'from_address' => $request->input('smtp_from_address'),
                'from_name' => $request->input('smtp_from_name'),
            ];
        }

        // Build email variables
        $emailVariables = null;
        if ($request->filled('email_company_name') || $request->filled('email_website_url') || 
            $request->filled('email_support_email') || $request->filled('email_contact_email') ||
            $request->filled('email_team_name') || $request->filled('email_contact_phone') ||
            $request->filled('email_contact_address')) {
            $emailVariables = [
                'company_name' => $request->input('email_company_name'),
                'website_url' => $request->input('email_website_url'),
                'support_email' => $request->input('email_support_email'),
                'contact_email' => $request->input('email_contact_email'),
                'team_name' => $request->input('email_team_name'),
                'contact_phone' => $request->input('email_contact_phone'),
                'contact_address' => $request->input('email_contact_address'),
            ];
            // Remove null values
            $emailVariables = array_filter($emailVariables, function($value) {
                return $value !== null && $value !== '';
            });
        }

        $sourceData = [
            'name' => $validated['name'],
            'return_url' => $validated['return_url'],
            'is_active' => $validated['is_active'],
            'admin_id' => $validated['admin_id'],
            'smtp_config' => $smtpConfig,
            'email_variables' => $emailVariables ?: null,
        ];

        Source::create($sourceData);
        
        // Clear iframe domains cache
        Cache::forget('allowed_iframe_domains');

        return redirect()->route('admin.sources.index')
            ->with('success', 'Source created successfully.');
    }

    public function edit(Source $source)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_sources')) {
            abort(403, 'You do not have permission to manage sources.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $source->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this source.');
        }

        return view('admin.sources.edit', compact('source'));
    }

    public function update(Request $request, Source $source)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_sources')) {
            abort(403, 'You do not have permission to manage sources.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $source->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to update this source.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sources,name,' . $source->id,
            'return_url' => 'required|url|max:2048',
            'is_active' => 'sometimes|boolean',
            // SMTP Config
            'smtp_mailer' => 'nullable|string|in:smtp,sendmail,mailgun,ses,postmark',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_encryption' => 'nullable|string|in:tls,ssl',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            // Email Variables
            'email_company_name' => 'nullable|string|max:255',
            'email_website_url' => 'nullable|url|max:2048',
            'email_support_email' => 'nullable|email|max:255',
            'email_contact_email' => 'nullable|email|max:255',
            'email_team_name' => 'nullable|string|max:255',
            'email_contact_phone' => 'nullable|string|max:50',
            'email_contact_address' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? $source->is_active);

        // Build SMTP config
        $smtpConfig = null;
        if ($request->filled('smtp_host')) {
            $smtpConfig = [
                'mailer' => $request->input('smtp_mailer', 'smtp'),
                'host' => $request->input('smtp_host'),
                'port' => $request->input('smtp_port', 587),
                'encryption' => $request->input('smtp_encryption', 'tls'),
                'username' => $request->input('smtp_username'),
                'password' => $request->input('smtp_password'),
                'from_address' => $request->input('smtp_from_address'),
                'from_name' => $request->input('smtp_from_name'),
            ];
        }

        // Build email variables
        $emailVariables = null;
        if ($request->filled('email_company_name') || $request->filled('email_website_url') || 
            $request->filled('email_support_email') || $request->filled('email_contact_email') ||
            $request->filled('email_team_name') || $request->filled('email_contact_phone') ||
            $request->filled('email_contact_address')) {
            $emailVariables = [
                'company_name' => $request->input('email_company_name'),
                'website_url' => $request->input('email_website_url'),
                'support_email' => $request->input('email_support_email'),
                'contact_email' => $request->input('email_contact_email'),
                'team_name' => $request->input('email_team_name'),
                'contact_phone' => $request->input('email_contact_phone'),
                'contact_address' => $request->input('email_contact_address'),
            ];
            // Remove null values
            $emailVariables = array_filter($emailVariables, function($value) {
                return $value !== null && $value !== '';
            });
        }

        $source->update([
            'name' => $validated['name'],
            'return_url' => $validated['return_url'],
            'is_active' => $validated['is_active'],
            'smtp_config' => $smtpConfig,
            'email_variables' => $emailVariables ?: null,
        ]);
        
        // Clear iframe domains cache
        Cache::forget('allowed_iframe_domains');

        return redirect()->route('admin.sources.index')
            ->with('success', 'Source updated successfully.');
    }

    public function destroy(Source $source)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_sources')) {
            abort(403, 'You do not have permission to manage sources.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $source->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to delete this source.');
        }

        $source->delete();
        
        // Clear iframe domains cache
        Cache::forget('allowed_iframe_domains');
        
        return redirect()->route('admin.sources.index')
            ->with('success', 'Source deleted successfully.');
    }
}


