@extends('layouts.admin')

@section('title', 'Create Category')
@section('subtitle', 'Add a new product category')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.product-categories.store') }}" method="POST"  class="form-confirm-submit">
        @csrf
        
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Slug -->
                <div class="col-span-1 md:col-span-2">
                    <label for="slug" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Slug (Optional)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="Leave blank to auto-generate" 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">The URL-friendly version of the name. Must be unique.</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Icon Class (Optional)</label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g. bi bi-star" 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Bootstrap Icon class name.</p>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Lower numbers appear first.</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div class="col-span-1 md:col-span-2 pt-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-surface-300 dark:border-surface-600 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-surface-700 dark:text-surface-300">Active</label>
                            <p class="text-surface-500 dark:text-surface-400">Make this category visible on the store.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
            <a href="{{ route('admin.product-categories.index') }}" class="inline-flex justify-center py-2 px-4 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Cancel
            </a>
            
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
