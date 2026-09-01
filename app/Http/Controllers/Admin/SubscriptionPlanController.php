<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SubscriptionPlanController extends Controller
{
    /**
     * Show pricing plans for a product.
     */
    public function index(Product $product)
    {
        $plans = $product->plans()->orderBy('sort_order')->orderBy('duration_months')->get();

        return view('admin.products.plans.index', [
            'product' => $product,
            'plans'   => $plans,
        ]);
    }

    /**
     * Store a new pricing plan.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'duration_months'  => ['required', 'integer', 'min:1'],
            'price'            => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        $validated['product_id']       = $product->id;
        $validated['is_active']        = $request->boolean('is_active', true);
        $validated['discount_percent'] = $validated['discount_percent'] ?? 0;
        $validated['sort_order']       = $validated['sort_order'] ?? 0;

        SubscriptionPlan::create($validated);

        return redirect()
            ->route('admin.products.plans.index', $product->id)
            ->with('success', 'Pricing plan created successfully.');
    }

    /**
     * Update an existing pricing plan.
     */
    public function update(Request $request, Product $product, SubscriptionPlan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'duration_months'  => ['required', 'integer', 'min:1'],
            'price'            => ['required', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        $validated['is_active']        = $request->boolean('is_active', true);
        $validated['discount_percent'] = $validated['discount_percent'] ?? 0;
        $validated['sort_order']       = $validated['sort_order'] ?? 0;

        $plan->update($validated);

        return redirect()
            ->route('admin.products.plans.index', $product->id)
            ->with('success', 'Pricing plan "' . $plan->name . '" updated successfully.');
    }

    /**
     * Delete a pricing plan.
     */
    public function destroy(Product $product, SubscriptionPlan $plan): RedirectResponse
    {
        $planName = $plan->name;
        $plan->delete();

        return redirect()
            ->route('admin.products.plans.index', $product->id)
            ->with('success', 'Pricing plan "' . $planName . '" deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggle(Product $product, SubscriptionPlan $plan): RedirectResponse
    {
        $plan->update(['is_active' => ! $plan->is_active]);

        return redirect()
            ->route('admin.products.plans.index', $product->id)
            ->with('success', 'Plan status updated to ' . ($plan->is_active ? 'Active' : 'Inactive') . '.');
    }
}
