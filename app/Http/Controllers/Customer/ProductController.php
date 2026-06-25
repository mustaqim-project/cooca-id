<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\View\View;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display listing of available products.
     */
    public function index(): View
    {
        $products = $this->productRepository->getActiveProducts();

        return view('customer.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): View
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product || !$product->is_active) {
            abort(404, 'Product not found');
        }

        return view('customer.products.show', [
            'product' => $product,
            'plans' => $product->subscriptionPlans()->where('is_active', true)->get(),
        ]);
    }
}
