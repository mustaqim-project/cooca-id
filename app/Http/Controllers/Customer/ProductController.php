<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display listing of available products (unified My Services hub).
     */
    public function index()
    {
        $products = $this->productRepository->getActiveProducts()->load('subscriptionPlans', 'category');

        return view('customer.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Display product detail with plan picker.
     */
    public function show(string $slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product || !$product->is_active) {
            abort(404, 'Product not found');
        }

        $plans = $product->subscriptionPlans()
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('customer.products.show', [
            'product' => $product,
            'plans'   => $plans,
        ]);
    }
}
