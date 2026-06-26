<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        return view('admin.products.create');
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

        return view('admin.products.edit', [
            'product' => $product,
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = $this->productRepository->create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
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

        $this->productRepository->update($id, $request->validated());

        return redirect()->route('admin.products.index')
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
