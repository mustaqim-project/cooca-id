@extends('layouts.admin')

@section('title', 'Edit Post')
@section('subtitle', 'Update existing blog article')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form class="form-confirm-submit" action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Content Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Article Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-lg font-medium"
                            placeholder="Enter an engaging title...">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Content <span class="text-red-500">*</span></label>
                        <textarea name="content" id="content" rows="15" required
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Excerpt (Optional)</label>
                        <textarea name="excerpt" id="excerpt" rows="3" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                            placeholder="A short summary for previews and social sharing...">{{ old('excerpt', $post->excerpt) }}</textarea>
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">If left blank, the first few lines of content will be used.</p>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900 cursor-pointer flex justify-between items-center" onclick="document.getElementById('seo-section').classList.toggle('hidden')">
                    <h3 class="text-lg font-medium text-surface-900 dark:text-white">Search Engine Optimization (SEO)</h3>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-surface-500 dark:text-surface-400"></i>
                </div>
                <div id="seo-section" class="p-6 space-y-6 hidden">
                    <div>
                        <label for="slug" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Custom URL Slug</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-900 text-surface-500 dark:text-surface-400 sm:text-sm">
                                {{ url('/blog') }}/
                            </span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" 
                                class="flex-1 block w-full rounded-none rounded-r-md py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                placeholder="my-awesome-post">
                        </div>
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Leave blank to auto-generate from title.</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('meta_description', $post->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Publish Settings -->
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                    <h3 class="text-base font-medium text-surface-900 dark:text-white">Publish</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Status</span>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio text-primary-600 focus:ring-primary-500" name="is_published" value="0" {{ old('is_published', $post->is_published) == '0' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-surface-700 dark:text-surface-300">Draft</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio text-green-600 focus:ring-green-500" name="is_published" value="1" {{ old('is_published', $post->is_published) == '1' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-surface-700 dark:text-surface-300 font-medium">Publish</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex items-start mt-4 pt-4 border-t border-surface-100">
                        <div class="flex items-center h-5">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}
                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-surface-300 dark:border-surface-600 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_featured" class="font-medium text-surface-700 dark:text-surface-300">Featured Post</label>
                            <p class="text-surface-500 dark:text-surface-400 text-xs">Pin to top of blog index.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-surface-100">
                        
                    </div>
                </div>
            </div>
            
            <!-- Category & Tags -->
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                    <h3 class="text-base font-medium text-surface-900 dark:text-white">Classification</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Category</label>
                        <select name="category" id="category" class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm select2-tags">
                            <option value="">Select or type new...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $post->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                            <!-- If the current post category is not in the list, add it -->
                            @if($post->category && !in_array($post->category, $categories->toArray()))
                                <option value="{{ $post->category }}" selected>{{ $post->category }}</option>
                            @endif
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Tags</label>
                        <select name="tags[]" id="tags" multiple="multiple" class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm select2-tags">
                            @php
                                $tags = old('tags', $post->tags ?? []);
                                $tags = is_string($tags) ? json_decode($tags, true) ?? [] : $tags;
                            @endphp
                            @foreach($tags as $tag)
                                <option value="{{ $tag }}" selected>{{ $tag }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Press enter to add multiple tags.</p>
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Featured Image -->
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                    <h3 class="text-base font-medium text-surface-900 dark:text-white">Featured Image URL</h3>
                </div>
                <div class="p-6">
                    <div>
                        <input type="text" name="featured_image" id="featured_image" value="{{ old('featured_image', $post->featured_image) }}" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                            placeholder="https://example.com/image.jpg"
                            onchange="previewImage(this.value)">
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div id="image-preview-container" class="mt-4 {{ old('featured_image', $post->featured_image) ? '' : 'hidden' }}">
                        <img id="image-preview" src="{{ old('featured_image', $post->featured_image) }}" alt="Preview" class="w-full h-auto rounded border border-surface-200 dark:border-surface-700">
                    </div>
                </div>
            </div>
            
        </div>
    </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <div class="corporate-card">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting.</p>
                        
                        <div class="flex flex-col space-y-3">
                            
                            <a href="javascript:history.back()" class="btn btn-secondary w-full">
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
