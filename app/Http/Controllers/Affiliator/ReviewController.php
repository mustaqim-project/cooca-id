<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
        $query = Review::whereIn('customer_id', $customerIds)
            ->with(['customer', 'product'])
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
            'total' => Review::whereIn('customer_id', $customerIds)->count(),
            'approved' => Review::whereIn('customer_id', $customerIds)->where('status', 'approved')->count(),
            'average_rating' => round(Review::whereIn('customer_id', $customerIds)
                ->where('status', 'approved')
                ->avg('rating') ?? 0, 2),
        ];

        return Inertia::render('Affiliator/Reviews/Index', [
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
            return Inertia::render('Affiliator/Reviews/MyReviews', [
                'reviews' => [],
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
            ]);
        }

        $query = Review::where('customer_id', $customer->id)
            ->with(['product'])
            ->latest('created_at');

        $reviews = $query->paginate(20);

        $stats = [
            'total' => Review::where('customer_id', $customer->id)->count(),
            'pending' => Review::where('customer_id', $customer->id)->where('status', 'pending')->count(),
            'approved' => Review::where('customer_id', $customer->id)->where('status', 'approved')->count(),
            'rejected' => Review::where('customer_id', $customer->id)->where('status', 'rejected')->count(),
        ];

        return Inertia::render('Affiliator/Reviews/MyReviews', [
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
        $existingReview = Review::where('customer_id', $customer->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return back()->withErrors(['product_id' => 'You have already reviewed this product.']);
        }

        Review::create([
            'customer_id' => $customer->id,
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return redirect()->route('affiliator.reviews.my_reviews')
            ->with('success', 'Review submitted successfully.');
    }
}
