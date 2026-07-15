<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * Affiliator Review Controller
 * 
 * Manages reviews from affiliator perspective.
 */
class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for products promoted by the affiliator.
     */
    public function index(Request $request)
    {
        $affiliator = Auth::guard('affiliator')->user();

        // Get all customers referred by this affiliator (direct referrals)
        $customerIds = \App\Models\Customer::where('referrer_id', $affiliator->id)
            ->pluck('id');

        // Get reviews from referred customers
        $query = Review::where('reviewer_type', 'customer')
            ->whereIn('reviewer_id', $customerIds)
            ->with(['reviewer', 'reviewable'])
            ->latest('created_at');

        // Filters
        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($product = $request->get('product_id')) {
            $query->where('reviewable_type', \App\Models\Product::class)
                  ->where('reviewable_id', $product);
        }

        $reviews = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Review::where('reviewer_type', 'customer')->whereIn('reviewer_id', $customerIds)->count(),
            'approved' => Review::where('reviewer_type', 'customer')->whereIn('reviewer_id', $customerIds)->where('is_approved', true)->count(),
            'average_rating' => round((float) (Review::where('reviewer_type', 'customer')
                ->whereIn('reviewer_id', $customerIds)
                ->where('is_approved', true)
                ->avg('rating') ?? 0), 2),
        ];

        return view('affiliator.reviews.index', [
            'reviews' => $reviews,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
                'product_id' => $request->get('product_id'),
            ],
        ]);
    }

    /**
     * Display affiliator's own reviews (if they are also a customer).
     */
    public function myReviews()
    {
        $affiliator = Auth::guard('affiliator')->user();

        // Check if affiliator is also a customer
        $customer = \App\Models\Customer::where('email', $affiliator->email)->first();

        if (!$customer) {
            return view('affiliator.reviews.my_reviews', [
                'reviews' => [],
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
            ]);
        }

        $query = Review::where('reviewer_type', 'customer')
            ->where('reviewer_id', $customer->id)
            ->with(['reviewable'])
            ->latest('created_at');

        $reviews = $query->paginate(20);

        $stats = [
            'total' => Review::where('reviewer_type', 'customer')->where('reviewer_id', $customer->id)->count(),
            'pending' => Review::where('reviewer_type', 'customer')->where('reviewer_id', $customer->id)->where('is_approved', false)->count(),
            'approved' => Review::where('reviewer_type', 'customer')->where('reviewer_id', $customer->id)->where('is_approved', true)->count(),
            'rejected' => 0,
        ];

        return view('affiliator.reviews.my_reviews', [
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a review (if affiliator is also a customer).
     */
    public function store(Request $request)
    {
        $affiliator = Auth::guard('affiliator')->user();

        // Check if affiliator is also a customer
        $customer = \App\Models\Customer::where('email', $affiliator->email)->first();

        if (!$customer) {
            return back()->withErrors(['error' => 'You need to have a customer account to write reviews.']);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        // Check if already reviewed
        $existingReview = Review::where('reviewer_type', 'customer')
            ->where('reviewer_id', $customer->id)
            ->where('reviewable_type', \App\Models\Product::class)
            ->where('reviewable_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return back()->withErrors(['product_id' => 'You have already reviewed this product.']);
        }

        Review::create([
            'reviewer_type' => 'customer',
            'reviewer_id' => $customer->id,
            'reviewable_type' => \App\Models\Product::class,
            'reviewable_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_approved' => false,
        ]);

        return redirect()->route('affiliator.reviews.my_reviews')
            ->with('success', 'Review submitted successfully.');
    }
}
