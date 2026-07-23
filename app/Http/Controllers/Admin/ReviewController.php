<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


/**
 * Admin Review Controller
 * 
 * Manages and moderates customer reviews.
 */
class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['reviewer', 'reviewable'])->latest('created_at');

        // Filters
        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($rating = $request->get('rating')) {
            $query->where('rating', $rating);
        }

        if ($product = $request->get('product_id')) {
            $query->where('reviewable_type', \App\Models\Product::class)
                  ->where('reviewable_id', $product);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHasMorph('reviewer', [\App\Models\Customer::class, \App\Models\Affiliator::class], function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
            'rejected' => 0,
            'average_rating' => round((float) (Review::where('is_approved', true)->avg('rating') ?? 0), 2),
        ];

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
                'rating' => $request->get('rating'),
                'product_id' => $request->get('product_id'),
                'search' => $request->get('search'),
            ],
        ]);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        return view('admin.reviews.show', [
            'review' => $review->load(['reviewer', 'reviewable']),
        ]);
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review)
    {
        $review->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject the specified review.
     */
    public function reject(Review $review, Request $request)
    {
        // No reject status in DB, we'll just set it to unapproved or delete it.
        // Let's set it to unapproved (pending)
        $review->update([
            'is_approved' => false,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return back()->with('success', 'Review rejected and set to pending.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}

