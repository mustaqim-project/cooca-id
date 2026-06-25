<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


/**
 * Customer Review Controller
 * 
 * Manages customer reviews for products.
 */
class ReviewController extends Controller
{
    /**
     * Display a listing of customer's reviews.
     */
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $query = Review::where('customer_id', $customer->id)
            ->with(['product'])
            ->latest('created_at');

        // Filters
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($product = $request->get('product_id')) {
            $query->where('product_id', $product);
        }

        $reviews = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Review::where('customer_id', $customer->id)->count(),
            'pending' => Review::where('customer_id', $customer->id)->where('status', 'pending')->count(),
            'approved' => Review::where('customer_id', $customer->id)->where('status', 'approved')->count(),
            'rejected' => Review::where('customer_id', $customer->id)->where('status', 'rejected')->count(),
        ];

        return view('customer.reviews.index', [
            'reviews' => $reviews,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
                'product_id' => $request->get('product_id'),
            ],
        ]);
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        // Check if customer has active subscription for this product
        $hasSubscription = Subscription::where('customer_id', $customer->id)
            ->where('product_id', $validated['product_id'])
            ->whereIn('status', ['active', 'trial'])
            ->exists();

        if (!$hasSubscription) {
            return back()->withErrors(['product_id' => 'You can only review products you have subscribed to.']);
        }

        // Check if already reviewed
        $existingReview = Review::where('customer_id', $customer->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return back()->withErrors(['product_id' => 'You have already reviewed this product.']);
        }

        $review = Review::create([
            'customer_id' => $customer->id,
            'product_id' => $validated['product_id'],
            'subscription_id' => $validated['subscription_id'] ?? null,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return redirect()->route('customer.reviews.index')
            ->with('success', 'Review submitted successfully. It will be published after moderation.');
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review)
    {
        $customer = Auth::guard('customer')->user();

        // Use Policy for authorization (prevents IDOR)
        Gate::authorize('update', $review);

        // Can only edit pending or rejected reviews
        if ($review->status === 'approved') {
            return back()->withErrors(['error' => 'Approved reviews cannot be edited.']);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'status' => 'pending', // Reset to pending for re-moderation
        ]);

        return redirect()->route('customer.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        $customer = Auth::guard('customer')->user();

        // Use Policy for authorization (prevents IDOR)
        Gate::authorize('delete', $review);

        // Can only delete pending or rejected reviews
        if ($review->status === 'approved') {
            return back()->withErrors(['error' => 'Approved reviews cannot be deleted.']);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
