@extends('layouts.admin')

@section('title', 'Create Page')
@section('subtitle', 'Add a new CMS page.')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.cms.pages.index') }}" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Pages
        </a>
    </div>

    <form action="{{ route('admin.cms.pages.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="card-header border-b border-surface-200 dark:border-surface-700 px-6 py-4">
                        <h3 class="card-title text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="form-label">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="Enter page title" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" value="{{ old('slug') }}" class="form-input" placeholder="Enter page slug (optional)">
                                <p class="text-xs text-surface-500 mt-1">Leave blank to generate automatically.</p>
                            </div>
                            
                            <div>
                                <label class="form-label">Content</label>
                                <textarea name="content" rows="10" class="form-input" placeholder="Write page content here... (HTML allowed)">{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="corporate-card">
                    <div class="card-header border-b border-surface-200 dark:border-surface-700 px-6 py-4">
                        <h3 class="card-title text-lg font-medium text-surface-900 dark:text-white">SEO Settings</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-input" placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                            </div>
                            
                            <div>
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="form-input" placeholder="keyword1, keyword2, ...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <div class="corporate-card">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Publishing</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="form-label">Status</label>
                                <select name="is_published" class="form-input">
                                    <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ old('is_published', '0') == '0' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="btn-primary w-full justify-center">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Page
                            </button>
                            <a href="{{ route('admin.cms.pages.index') }}" class="btn-secondary w-full justify-center">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
