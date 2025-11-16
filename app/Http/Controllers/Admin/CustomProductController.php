<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CustomProduct::query();

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_products,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:service,digital,other',
            'is_active' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,textarea,email,number,select,radio,checkbox',
            'custom_fields.*.required' => 'nullable|boolean',
            'custom_fields.*.options' => 'nullable|string',
            'custom_fields.*.width' => 'nullable|in:full,half,third,quarter',
            'custom_fields.*.layout' => 'nullable|in:vertical,horizontal',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Process custom fields
        if ($request->has('custom_fields') && is_array($request->custom_fields)) {
            $customFields = [];
            foreach ($request->custom_fields as $field) {
                if (!empty($field['label'])) {
                    $fieldData = [
                        'label' => $field['label'],
                        'type' => $field['type'] ?? 'text',
                        'required' => isset($field['required']) && $field['required'] == '1',
                        'width' => $field['width'] ?? 'full',
                        'layout' => $field['layout'] ?? 'vertical',
                    ];
                    
                    // Process options for select/radio/checkbox fields
                    if (in_array($fieldData['type'], ['select', 'radio', 'checkbox']) && !empty($field['options'])) {
                        $options = array_filter(array_map('trim', explode("\n", $field['options'])));
                        if (!empty($options)) {
                            $fieldData['options'] = array_values($options);
                        }
                    }
                    
                    $customFields[] = $fieldData;
                }
            }
            $validated['custom_fields'] = !empty($customFields) ? $customFields : null;
        } else {
            $validated['custom_fields'] = null;
        }

        $product = CustomProduct::create($validated);

        return redirect()->route('admin.custom-products.index')
            ->with('success', 'Custom product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomProduct $customProduct)
    {
        return view('admin.custom-products.edit', compact('customProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomProduct $customProduct)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:custom_products,slug,' . $customProduct->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:service,digital,other',
            'is_active' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,textarea,email,number,select,radio,checkbox',
            'custom_fields.*.required' => 'nullable|boolean',
            'custom_fields.*.options' => 'nullable|string',
            'custom_fields.*.width' => 'nullable|in:full,half,third,quarter',
            'custom_fields.*.layout' => 'nullable|in:vertical,horizontal',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Process custom fields
        if ($request->has('custom_fields') && is_array($request->custom_fields)) {
            $customFields = [];
            foreach ($request->custom_fields as $field) {
                if (!empty($field['label'])) {
                    $fieldData = [
                        'label' => $field['label'],
                        'type' => $field['type'] ?? 'text',
                        'required' => isset($field['required']) && $field['required'] == '1',
                        'width' => $field['width'] ?? 'full',
                        'layout' => $field['layout'] ?? 'vertical',
                    ];
                    
                    // Process options for select/radio/checkbox fields
                    if (in_array($fieldData['type'], ['select', 'radio', 'checkbox']) && !empty($field['options'])) {
                        $options = array_filter(array_map('trim', explode("\n", $field['options'])));
                        if (!empty($options)) {
                            $fieldData['options'] = array_values($options);
                        }
                    }
                    
                    $customFields[] = $fieldData;
                }
            }
            $validated['custom_fields'] = !empty($customFields) ? $customFields : null;
        } else {
            $validated['custom_fields'] = null;
        }

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
