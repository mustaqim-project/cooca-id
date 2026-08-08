<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display listing of products.
     */
    public function index()
    {
        $products = $this->productRepository->paginateWithFilters(15);
        $categories = \App\Models\ProductCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = \App\Models\ProductCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(string $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        $categories = \App\Models\ProductCategory::where('is_active', true)->orderBy('sort_order')->get();
        $plans = $product->subscriptionPlans()->orderBy('sort_order')->orderBy('duration_months')->get();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'plans'   => $plans,
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $plansData = $data['plans'] ?? [];

        // Map 'price' field to 'base_price' if needed
        if (!isset($data['base_price']) && isset($data['price'])) {
            $data['base_price'] = $data['price'];
        }

        // Handle image upload (converted to WebP)
        if ($request->hasFile('thumbnail')) {
            $path = app(ImageService::class)->saveToStorage(
                $request->file('thumbnail'), 'public', 'products', uniqid('product_')
            );
            $data['thumbnail'] = '/storage/' . $path;
        }

        unset($data['plans'], $data['price']);

        $product = DB::transaction(function () use ($data, $plansData) {
            $product = $this->productRepository->create($data);

            // Save pricing plans if submitted
            foreach ($plansData as $i => $plan) {
                SubscriptionPlan::create([
                    'product_id'       => $product->id,
                    'name'             => $plan['name'],
                    'duration_months'  => (int) $plan['duration_months'],
                    'price'            => (float) $plan['price'],
                    'discount_percent' => (float) ($plan['discount_percent'] ?? 0),
                    'sort_order'       => (int) ($plan['sort_order'] ?? $i),
                    'is_active'        => isset($plan['is_active']) ? (bool) $plan['is_active'] : true,
                ]);
            }

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product->id)
            ->with('success', 'Product created successfully. You can now manage pricing plans below.');
    }

    /**
     * Update the specified product.
     */
    public function update(StoreProductRequest $request, string $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found.');
        }

        $data = $request->validated();
        
        if (empty($data['category_id']) && $request->filled('product_category_id')) {
            $data['category_id'] = $request->input('product_category_id');
        }

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                $oldPath = str_replace('/storage/', '', $product->thumbnail);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = app(ImageService::class)->saveToStorage(
                $request->file('thumbnail'), 'public', 'products', uniqid('product_')
            );
            $data['thumbnail'] = '/storage/' . $path;
        } else {
            unset($data['thumbnail']);
        }

        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = array_values($data['features']);
        }

        unset($data['plans'], $data['price']);

        $this->productRepository->update($id, $data);

        return redirect()->route('admin.products.edit', $id)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found.');
        }

        $this->productRepository->delete($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
