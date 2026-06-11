<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display listing of available products.
     */
    public function index(): Response
    {
        $products = $this->productRepository->getActiveProducts();

        return Inertia::render('Customer/Products/Index', [
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): Response
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product || !$product->is_active) {
            abort(404, 'Product not found');
        }

        return Inertia::render('Customer/Products/Show', [
            'product' => new ProductResource($product),
            'plans' => $product->subscriptionPlans()->where('is_active', true)->get(),
        ]);
    }
}
