@extends('layouts.admin')

@section('title', 'Page Details')
@section('subtitle', 'View specific CMS page data.')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.cms.pages.index') }}" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Pages
        </a>
        <a href="{{ route('admin.cms.pages.edit', $page->id) }}" class="inline-flex items-center px-4 py-2 border border-surface-300 dark:border-surface-600 rounded-md font-medium text-xs text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 shadow-sm transition">
            <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit
        </a>
    </div>

    <!-- Details Card -->
    <div class="corporate-card overflow-hidden">
        <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700">
            <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">Information Overview</h3>
            <p class="mt-1 max-w-2xl text-sm text-surface-500 dark:text-surface-400">Detailed breakdown of the CMS page.</p>
        </div>
        <div class="px-6 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700 dark:sm:divide-surface-700">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">ID</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">#{{ $page->id }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Title</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $page->title }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Slug</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">/{{ $page->slug }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Status</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Meta Description</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $page->meta_description ?? '-' }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Meta Keywords</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $page->meta_keywords ?? '-' }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $page->created_at ? $page->created_at->format('M d, Y H:i') : '-' }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Content</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 prose dark:prose-invert max-w-none">
                        {!! nl2br(e($page->content)) !!}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
