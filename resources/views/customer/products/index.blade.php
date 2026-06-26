@extends('layouts.customer')

@section('title', 'Available Products')
@section('subtitle', 'Browse our catalog of digital products and services')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="w-full sm:max-w-xs">
        <label for="search" class="sr-only">Search products</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input id="search" name="search" class="block w-full bg-white dark:bg-surface-800 border border-surface-300 dark:border-surface-700 rounded-md py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:outline-none focus:text-surface-900 dark:text-white focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search for products..." type="search">
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($products as $product)
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up rounded-lg border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
        <div class="aspect-w-16 aspect-h-9 bg-surface-100 dark:bg-surface-700">
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="object-cover w-full h-48">
            @else
                <div class="flex items-center justify-center w-full h-48 bg-primary-50 dark:bg-primary-900/20 text-primary-300">
                    <i data-lucide="box" class="w-4 h-4 text-5xl"></i>
                </div>
            @endif
        </div>
        
        <div class="p-5 flex-1 flex flex-col">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-lg font-bold text-surface-900 dark:text-white line-clamp-1">
                    {{ $product->name }}
                </h3>
                @if($product->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                        {{ $product->category->name }}
                    </span>
                @endif
            </div>
            
            <p class="text-sm text-surface-500 dark:text-surface-400 mb-4 line-clamp-2 flex-1">
                {{ $product->description ?? 'No description available for this product.' }}
            </p>
            
            <div class="mt-auto">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm">
                        <span class="text-surface-500 dark:text-surface-400">Starting from</span>
                        <div class="text-lg font-bold text-surface-900 dark:text-white">
                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('customer.products.show', $product->slug) }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="text-center bg-white dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700 p-12 shadow-sm">
            <i data-lucide="inbox" class="w-4 h-4 text-5xl text-surface-300 dark:text-surface-600 dark:text-surface-400 mb-4 inline-block"></i>
            <h3 class="mt-2 text-sm font-medium text-surface-900 dark:text-white">No products</h3>
            <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">No products are currently available in the catalog.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection