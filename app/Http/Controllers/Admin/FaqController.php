<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\Cms\ContentService;
use App\Http\Requests\Admin\FaqRequest;
use Illuminate\Http\JsonResponse;



class FaqController extends Controller
{
    public function __construct(
        protected ContentService $contentService
    ) {}

    /**
     * Display FAQs page
     */
    public function index(): Response
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::with(['creator', 'updater'])
                ->orderBy('order')
                ->get(),
            'categories' => Faq::select('category')
                ->distinct()
                ->pluck('category'),
        ]);
    }

    /**
     * Show form for creating a new FAQ
     */
    public function create(): Response
    {
        return view('admin.faqs.create', [
            'faq' => null,
        ]);
    }

    /**
     * Show form for editing a FAQ
     */
    public function edit(string $id): Response
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.create', [
            'faq' => $faq,
        ]);
    }

    /**
     * Get all FAQs
     */
    public function list(): JsonResponse
    {
        $faqs = Faq::with(['creator', 'updater'])
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $faqs,
        ]);
    }

    /**
     * Store a new FAQ
     */
    public function store(FaqRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $faq = Faq::create($data);

        return response()->json([
            'success' => true,
            'message' => 'FAQ created successfully',
            'data' => $faq,
        ]);
    }

    /**
     * Update an existing FAQ
     */
    public function update(FaqRequest $request, string $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $faq->update($data);

        return response()->json([
            'success' => true,
            'message' => 'FAQ updated successfully',
            'data' => $faq,
        ]);
    }

    /**
     * Delete a FAQ
     */
    public function destroy(string $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully',
        ]);
    }

    /**
     * Reorder FAQs
     */
    public function reorder(JsonResponse $request): JsonResponse
    {
        $orderData = $request->input('order', []);

        foreach ($orderData as $item) {
            Faq::where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'FAQs reordered successfully',
        ]);
    }
}
