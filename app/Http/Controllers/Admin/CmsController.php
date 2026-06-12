<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * Admin CMS Controller
 * 
 * Manages static pages and landing page content.
 */
class CmsController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index(Request $request)
    {
        $query = Page::latest('created_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('is_published', $status === 'published');
        }

        $pages = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Cms/Pages/Index', [
            'pages' => $pages,
            'filters' => [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return Inertia::render('Admin/Cms/Pages/Create', [
            'page' => null,
        ]);
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->boolean('is_published');

        Page::create($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified page.
     */
    public function show(Page $page)
    {
        return Inertia::render('Admin/Cms/Pages/Show', [
            'page' => $page->load('author'),
        ]);
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        return Inertia::render('Admin/Cms/Pages/Edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:pages,slug,{$page->id}",
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $page->update($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return back()->with('success', 'Page deleted successfully.');
    }
}
