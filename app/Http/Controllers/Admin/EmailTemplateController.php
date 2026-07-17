<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Admin Email Template Controller
 *
 * Manages email templates for system notifications and campaigns.
 */
class EmailTemplateController extends Controller
{
    /**
     * Display a listing of email templates.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::with(['creator', 'updater'])->latest('created_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('key', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $templates = $query->paginate(20)->withQueryString();

        $categories = EmailTemplate::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return view('admin.email-templates.index', [
            'templates' => $templates,
            'categories' => $categories,
            'filters' => [
                'search' => $request->get('search'),
                'category' => $request->get('category'),
                'status' => $request->get('status'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        return view('admin.email-templates.create', [
            'template' => null,
            'categories' => EmailTemplate::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->pluck('category'),
        ]);
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:100|unique:email_templates,key',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['variables'] = $request->input('variables', []);

        $template = EmailTemplate::create($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template created successfully');
    }

    /**
     * Display the specified template.
     */
    public function show(EmailTemplate $template)
    {
        return view('admin.email-templates.show', [
            'template' => $template->load(['creator', 'updater']),
        ]);
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(EmailTemplate $template)
    {
        return view('admin.email-templates.edit', [
            'template' => $template,
            'categories' => EmailTemplate::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->pluck('category'),
        ]);
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => "required|string|max:100|unique:email_templates,key,{$template->id}",
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['variables'] = $request->input('variables', []);

        $template->update($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template updated successfully');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(EmailTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template deleted successfully');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(EmailTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Active status updated successfully');
    }

    /**
     * Preview template with sample data
     */
    public function preview(EmailTemplate $template)
    {
        return view('admin.email-templates.show', [
            'template' => $template,
        ]);
    }
}
