@extends('layouts.admin')

@section('title', 'Category Details')
@section('subtitle', 'View category info and associated products')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <!-- Details Card -->
    <div class="corporate-card">
        <div class="card-header">
            <h3 class="card-title">Information Details</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.product-categories.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Categories
    </a>
    
    <div class="flex gap-2">
        <a href="{{ route('admin.product-categories.edit', $category->id) }}" class="inline-flex items-center px-4 py-2 border border-surface-300 dark:border-surface-600 rounded-md shadow-sm text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 focus:outline-none">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        
        @if(($category->products->count() ?? 0) === 0)
        <form class="form-confirm-delete" action="{{ route('admin.product-categories.destroy', $category->id) }}" method="POST" >
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
        @else
        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-surface-400 cursor-not-allowed" title="Cannot delete category with active products">
            <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
        </button>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Category Details -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-6">
            <div class="p-6 text-center border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                @if($category->icon)
                    <div class="mx-auto h-16 w-16 rounded-full bg-primary-100 flex items-center justify-center mb-4">
                        <i class="{{ $category->icon }} text-2xl text-primary-600"></i>
                    </div>
                @else
                    <div class="mx-auto h-16 w-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-2xl mb-4">
                        {{ strtoupper(substr($category->name, 0, 1)) }}
                    </div>
                @endif
                
                <h3 class="text-xl font-bold text-surface-900 dark:text-white">{{ $category->name }}</h3>
                <p class="text-sm text-surface-500 dark:text-surface-400 font-mono mt-1">{{ $category->slug }}</p>
                
                <div class="mt-4 flex justify-center">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Description</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $category->description ?: 'No description provided.' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Sort Order</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $category->sort_order ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $category->created_at->format('F d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $category->updated_at->format('F d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- Products List -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900 flex justify-between items-center">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Products in Category</h3>
                <span class="bg-primary-100 text-primary-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $category->products->count() ?? 0 }} Products
                </span>
            </div>
            
            @if(isset($category->products) && $category->products->count() > 0)
            <ul class="divide-y divide-surface-200 dark:divide-surface-700">
                @foreach($category->products as $product)
                <li class="p-4 hover:bg-surface-50 dark:bg-surface-900">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-400">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-surface-900 dark:text-white truncate">
                                {{ $product->name }}
                            </p>
                            <p class="text-sm text-surface-500 dark:text-surface-400 truncate">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('admin.products.show', $product->id) }}" class="text-primary-600 hover:text-primary-900 text-sm font-medium">View</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="p-12 text-center text-surface-500 dark:text-surface-400">
                <i data-lucide="package" class="w-4 h-4"></i>
                <p>No products exist in this category yet.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                        Add a product <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
