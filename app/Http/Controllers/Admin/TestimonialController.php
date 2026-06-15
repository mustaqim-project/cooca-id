<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\Cms\ContentService;
use App\Http\Requests\Admin\TestimonialRequest;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function __construct(
        protected ContentService $contentService
    ) {}

    /**
     * Display testimonials page
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::with(['customer', 'creator', 'updater'])
                ->orderBy('order')
                ->get(),
        ]);
    }

    /**
     * Show form for creating a new testimonial
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Testimonials/Create', [
            'testimonial' => null,
        ]);
    }

    /**
     * Show form for editing a testimonial
     */
    public function edit(string $id): Response
    {
        $testimonial = Testimonial::findOrFail($id);
        return Inertia::render('Admin/Testimonials/Create', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Get all testimonials
     */
    public function list(): JsonResponse
    {
        $testimonials = Testimonial::with(['customer', 'creator', 'updater'])
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials,
        ]);
    }

    /**
     * Store a new testimonial
     */
    public function store(TestimonialRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $testimonial = Testimonial::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully',
            'data' => $testimonial,
        ]);
    }

    /**
     * Update an existing testimonial
     */
    public function update(TestimonialRequest $request, string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $testimonial->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully',
            'data' => $testimonial,
        ]);
    }

    /**
     * Delete a testimonial
     */
    public function destroy(string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully',
        ]);
    }

    /**
     * Reorder testimonials
     */
    public function reorder(JsonResponse $request): JsonResponse
    {
        $orderData = $request->input('order', []);

        foreach ($orderData as $item) {
            Testimonial::where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Testimonials reordered successfully',
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_featured' => !$testimonial->is_featured]);

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated successfully',
            'data' => $testimonial,
        ]);
    }
}
