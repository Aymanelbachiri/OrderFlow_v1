<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\AdminScopesData;

class CustomProductController extends Controller
{
    use AdminScopesData;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_create_custom_products')) {
            abort(403, 'You do not have permission to manage custom products.');
        }

        $query = CustomProduct::query();
        
        // Scope to admin's data (unless super admin)
        $this->scopeToAdmin($query);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('product_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest()->paginate(15);

        return view('admin.custom-products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.custom-products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_create_custom_products')) {
            abort(403, 'You do not have permission to manage custom products.');
        }

        // Check limit
        $user = auth()->user();
        if (!$user->canCreateResource('custom_products')) {
            return redirect()->route('admin.custom-products.index')
                ->with('error', 'You have reached your maximum number of custom products.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_products,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:service,digital,other',
            'is_active' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['admin_id'] = $this->getCurrentAdminId();

        $product = CustomProduct::create($validated);

        return redirect()->route('admin.custom-products.index')
            ->with('success', 'Custom product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomProduct $customProduct)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_create_custom_products')) {
            abort(403, 'You do not have permission to manage custom products.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $customProduct->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this product.');
        }

        return view('admin.custom-products.edit', compact('customProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomProduct $customProduct)
    {
        // Check permission
        if (!auth()->user()->hasPermission('can_create_custom_products')) {
            abort(403, 'You do not have permission to manage custom products.');
        }

        // Check ownership (unless super admin)
        if (!$this->isSuperAdmin() && $customProduct->admin_id !== auth()->id()) {
            abort(403, 'You do not have permission to update this product.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:custom_products,slug,' . $customProduct->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:service,digital,other',
            'is_active' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $customProduct->update($validated);

        return redirect()->route('admin.custom-products.index')
            ->with('success', 'Custom product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomProduct $customProduct)
    {
        $customProduct->delete();

        return redirect()->route('admin.custom-products.index')
            ->with('success', 'Custom product deleted successfully.');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(CustomProduct $customProduct)
    {
        $customProduct->update([
            'is_active' => !$customProduct->is_active
        ]);

        return back()->with('success', 'Product status updated successfully.');
    }
}
