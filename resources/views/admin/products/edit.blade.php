@extends('layouts.admin')

@section('title', 'Edit Product')
@section('subtitle', 'Update details for ' . $product->name)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form class="form-confirm-submit" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
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
                        <div class="p-6 sm:p-8 space-y-6">
            <!-- Basic Info -->
            <div>
                <h4 class="text-lg font-medium text-surface-900 dark:text-white border-b border-surface-200 dark:border-surface-700 pb-2 mb-4">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4" required 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Category</label>
                        <select name="category_id" id="category_id" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\ProductCategory::where('is_active', true)->orderBy('sort_order')->get() ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Status</label>
                        <select name="is_active" id="is_active" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="1" {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $product->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive / Draft</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Info -->
            <div>
                <h4 class="text-lg font-medium text-surface-900 dark:text-white border-b border-surface-200 dark:border-surface-700 pb-2 mb-4 mt-8">Pricing Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Price (Rp) <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-surface-500 dark:text-surface-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" step="1"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-4 sm:text-sm border-surface-300 dark:border-surface-600 rounded-md py-2">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Pricing Model</label>
                        <div class="mt-2 space-x-4 flex">
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio text-primary-600 focus:ring-primary-500 h-4 w-4 border-surface-300 dark:border-surface-600" name="type" value="one_time" {{ old('type', $product->type ?? 'one_time') == 'one_time' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-surface-700 dark:text-surface-300">One-time Purchase</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio text-primary-600 focus:ring-primary-500 h-4 w-4 border-surface-300 dark:border-surface-600" name="type" value="subscription" {{ old('type', $product->type ?? '') == 'subscription' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-surface-700 dark:text-surface-300">Subscription</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Technical Integration Info -->
            <div>
                <h4 class="text-lg font-medium text-surface-900 dark:text-white border-b border-surface-200 dark:border-surface-700 pb-2 mb-4 mt-8">Integration Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label for="webhook_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Webhook URL (Optional)</label>
                        <input type="url" name="webhook_url" id="webhook_url" value="{{ old('webhook_url', $product->webhook_url) }}" placeholder="https://your-app.com/api/webhooks/cooca"
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">URL to receive notifications when a customer purchases this product.</p>
                        @error('webhook_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="demo_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Demo URL (Optional)</label>
                        <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://demo.cooca.id"
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">URL for customers to preview or demo this product.</p>
                        @error('demo_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
            <a href="{{ route('admin.products.show', $product->id) }}" class="inline-flex justify-center py-2 px-4 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
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
