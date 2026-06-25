<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;


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
        $query = Review::with(['customer', 'product'])->latest('created_at');

        // Filters
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($rating = $request->get('rating')) {
            $query->where('rating', $rating);
        }

        if ($product = $request->get('product_id')) {
            $query->where('product_id', $product);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'average_rating' => round(Review::where('status', 'approved')->avg('rating') ?? 0, 2),
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
     * Approve the specified review.
     */
    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject the specified review.
     */
    public function reject(Review $review, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $review->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        return back()->with('success', 'Review rejected.');
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
