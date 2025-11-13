<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResellerCreditPack;
use Illuminate\Http\Request;
use App\Traits\AdminScopesData;

class ResellerCreditPackController extends Controller
{
    use AdminScopesData;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_reseller_credit_packs')) {
            abort(403, 'You do not have permission to manage reseller credit packs.');
        }

        $query = ResellerCreditPack::query();
        
        // Scope to admin's data (unless super admin)
        $this->scopeToAdmin($query);

        $creditPacks = $query->latest()->paginate(20);

        return view('admin.reseller-credit-packs.index', compact('creditPacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reseller-credit-packs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_reseller_credit_packs')) {
            abort(403, 'You do not have permission to manage reseller credit packs.');
        }

        // Check limit
        $user = auth()->user();
        if (!$user->canCreateResource('reseller_credit_packs')) {
            return redirect()->route('admin.reseller-credit-packs.index')
                ->with('error', 'You have reached your maximum number of reseller credit packs.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'credits_amount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:stripe,paypal,crypto,bank_transfer',
            'is_active' => 'boolean',
        ]);

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features'], function($feature) {
                return !empty(trim($feature));
            });
        }

        $validated['admin_id'] = $this->getCurrentAdminId();

        ResellerCreditPack::create($validated);

        return redirect()->route('admin.reseller-credit-packs.index')
            ->with('success', 'Reseller credit pack created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResellerCreditPack $resellerCreditPack)
    {
        return view('admin.reseller-credit-packs.show', compact('resellerCreditPack'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResellerCreditPack $resellerCreditPack)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_reseller_credit_packs')) {
            abort(403, 'You do not have permission to manage reseller credit packs.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $resellerCreditPack->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this credit pack.');
        }

        return view('admin.reseller-credit-packs.edit', compact('resellerCreditPack'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResellerCreditPack $resellerCreditPack)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_reseller_credit_packs')) {
            abort(403, 'You do not have permission to manage reseller credit packs.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $resellerCreditPack->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to update this credit pack.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'credits_amount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:stripe,paypal,crypto,bank_transfer',
            'is_active' => 'boolean',
        ]);

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features'], function($feature) {
                return !empty(trim($feature));
            });
        }

        $resellerCreditPack->update($validated);

        return redirect()->route('admin.reseller-credit-packs.index')
            ->with('success', 'Reseller credit pack updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResellerCreditPack $resellerCreditPack)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_manage_reseller_credit_packs')) {
            abort(403, 'You do not have permission to manage reseller credit packs.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $resellerCreditPack->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to delete this credit pack.');
        }

        $resellerCreditPack->delete();

        return redirect()->route('admin.reseller-credit-packs.index')
            ->with('success', 'Reseller credit pack deleted successfully.');
    }
}
