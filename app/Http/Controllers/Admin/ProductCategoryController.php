<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


use Illuminate\Http\JsonResponse;

/**
 * Admin Product Category Controller
 * 
 * Manages product categories.
 */
class ProductCategoryController extends Controller
{
    /**
     * Display a listing of product categories.
     */
    public function index(Request $request): Response
    {
        $query = ProductCategory::withCount('products')->latest('created_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $categories = $query->orderBy('sort_order')->paginate(20)->withQueryString();

        return view('admin.productcategories.index', [
            'categories' => $categories,
            'filters' => [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): Response
    {
        return view('admin.productcategories.create', [
            'category' => null,
        ]);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $category = ProductCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product category created successfully',
            'data' => $category,
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show(ProductCategory $category): Response
    {
        return view('admin.productcategories.show', [
            'category' => $category->load('products'),
        ]);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(ProductCategory $category): Response
    {
        return view('admin.productcategories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, ProductCategory $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:product_categories,slug,{$category->id}",
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product category updated successfully',
            'data' => $category,
        ]);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(ProductCategory $category): JsonResponse
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with existing products',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product category deleted successfully',
        ]);
    }

    /**
     * Reorder categories
     */
    public function reorder(Request $request): JsonResponse
    {
        $orderData = $request->input('order', []);

        foreach ($orderData as $item) {
            ProductCategory::where('id', $item['id'])
                ->update(['sort_order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categories reordered successfully',
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(ProductCategory $category): JsonResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Active status updated successfully',
            'data' => $category,
        ]);
    }
}
