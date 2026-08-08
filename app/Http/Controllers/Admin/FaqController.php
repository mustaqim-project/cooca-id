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
    public function index()
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
    public function create()
    {
        return view('admin.faqs.create', [
            'faq' => null,
        ]);
    }

    /**
     * Show form for editing a FAQ
     */
    public function edit(string $id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', [
            'faq' => $faq,
        ]);
    }

    /**
     * Get all FAQs
     */
    public function list()
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
    public function store(FaqRequest $request)
    {
        $data = $request->validated();
        if (auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        $faq = Faq::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully',
                'data' => $faq,
            ]);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dibuat.');
    }

    /**
     * Update an existing FAQ
     */
    public function update(FaqRequest $request, string $id)
    {
        $faq = Faq::findOrFail($id);
        $data = $request->validated();
        if (auth()->check()) {
            $data['updated_by'] = auth()->id();
        }

        $faq->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully',
                'data' => $faq,
            ]);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    /**
     * Delete a FAQ
     */
    public function destroy(string $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully',
            ]);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dihapus.');
    }


    /**
     * Reorder FAQs
     */
    public function reorder(JsonResponse $request)
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
