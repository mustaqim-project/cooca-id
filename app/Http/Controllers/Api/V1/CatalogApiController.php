<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogApiController extends Controller
{
    // GET /api/v1/products
    public function products(Request $request): JsonResponse
    {
        $products = Product::active()
            ->ordered()
            ->with('category')
            ->paginate(15);

        return response()->json($products);
    }

    // GET /api/v1/products/{product}
    public function show(Product $product): JsonResponse
    {
        $product->load('category', 'subscriptionPlans');
        $product->increment('views');

        return response()->json(['data' => $product]);
    }

    // GET /api/v1/products/{product}/plans
    public function plans(Product $product): JsonResponse
    {
        $plans = $product->subscriptionPlans()
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return response()->json(['data' => $plans]);
    }
}
