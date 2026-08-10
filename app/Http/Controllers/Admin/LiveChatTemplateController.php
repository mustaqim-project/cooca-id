<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveChatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class LiveChatTemplateController extends Controller
{
    /**
     * Display list of live chat templates (Page & JSON).
     */
    public function index(Request $request)
    {
        $templates = LiveChatTemplate::orderBy('sort_order')->orderBy('title')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $templates,
            ]);
        }

        return view('admin.live-chats.templates', [
            'templates' => $templates,
        ]);
    }

    /**
     * Store a new live chat template.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:100',
            'content'    => 'required|string|max:2000',
            'shortcut'   => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $shortcut = $request->input('shortcut')
            ? Str::slug($request->input('shortcut'))
            : Str::slug($request->input('title'));

        // Ensure unique shortcut
        $existing = LiveChatTemplate::where('shortcut', $shortcut)->first();
        if ($existing) {
            $shortcut = $shortcut . '-' . Str::random(4);
        }

        $template = LiveChatTemplate::create([
            'title'      => trim($request->input('title')),
            'shortcut'   => $shortcut,
            'content'    => trim($request->input('content')),
            'is_active'  => $request->has('is_active') ? (bool) $request->input('is_active') : true,
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Template berhasil ditambahkan.', 'data' => $template]);
        }

        return redirect()->back()->with('success', 'Template balasan cepat berhasil ditambahkan.');
    }

    /**
     * Update an existing live chat template.
     */
    public function update(Request $request, string $id)
    {
        $template = LiveChatTemplate::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:100',
            'content'    => 'required|string|max:2000',
            'sort_order' => 'nullable|integer',
        ]);

        $template->update([
            'title'      => trim($request->input('title')),
            'content'    => trim($request->input('content')),
            'is_active'  => $request->has('is_active') ? (bool) $request->input('is_active') : true,
            'sort_order' => (int) $request->input('sort_order', $template->sort_order),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Template berhasil diperbarui.', 'data' => $template]);
        }

        return redirect()->back()->with('success', 'Template balasan cepat berhasil diperbarui.');
    }

    /**
     * Delete a live chat template.
     */
    public function destroy(Request $request, string $id)
    {
        $template = LiveChatTemplate::findOrFail($id);
        $template->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Template berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Template balasan cepat berhasil dihapus.');
    }

    /**
     * Show form to create a new template.
     */
    public function create()
    {
        return view('admin.live-chats.templates-create');
    }

    /**
     * Display the specified template.
     */
    public function show(string $id)
    {
        $template = LiveChatTemplate::findOrFail($id);
        return view('admin.live-chats.template-show', compact('template'));
    }

    /**
     * Show edit form for template.
     */
    public function edit(string $id)
    {
        $template = LiveChatTemplate::findOrFail($id);
        return view('admin.live-chats.templates-edit', compact('template'));
    }
}
