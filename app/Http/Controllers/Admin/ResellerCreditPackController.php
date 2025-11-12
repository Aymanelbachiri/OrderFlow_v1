<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResellerCreditPack;
use Illuminate\Http\Request;

class ResellerCreditPackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creditPacks = ResellerCreditPack::latest()->paginate(20);

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
        return view('admin.reseller-credit-packs.edit', compact('resellerCreditPack'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResellerCreditPack $resellerCreditPack)
    {
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
        $resellerCreditPack->delete();

        return redirect()->route('admin.reseller-credit-packs.index')
            ->with('success', 'Reseller credit pack deleted successfully.');
    }
}
