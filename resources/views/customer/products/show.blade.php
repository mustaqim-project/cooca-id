@extends('layouts.customer')

@section('title', $product->name)
@section('subtitle', 'Product Details & Subscription Plans')

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
            <a href="{{ route('customer.products.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Products
    </a>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8">
    <div class="md:flex">
        <div class="md:w-1/3 bg-surface-100 dark:bg-surface-700 border-b md:border-b-0 md:border-r border-surface-200 dark:border-surface-700">
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-64 md:h-full object-cover">
            @else
                <div class="flex items-center justify-center w-full h-64 md:h-full bg-primary-50 dark:bg-primary-900/20 text-primary-300">
                    <i data-lucide="box" class="w-4 h-4 text-7xl"></i>
                </div>
            @endif
        </div>
        <div class="p-6 md:p-8 md:w-2/3">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-surface-900 dark:text-white">{{ $product->name }}</h2>
                    <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">SKU: {{ $product->sku ?? 'N/A' }}</p>
                </div>
                @if($product->category)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                        {{ $product->category->name }}
                    </span>
                @endif
            </div>
            
            <div class="prose prose-sm sm:prose dark:prose-invert max-w-none text-surface-600 dark:text-surface-300 mb-6">
                {{ $product->description ?? 'No detailed description provided.' }}
            </div>
            
            <div class="pt-6 border-t border-surface-200 dark:border-surface-700">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-4">Available Subscription Plans</h3>
                
                @if($plans->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($plans as $plan)
                        <div class="border border-surface-200 dark:border-surface-700 rounded-lg p-4 hover:border-primary-500 dark:hover:border-primary-400 transition-colors flex flex-col relative {{ $plan->is_popular ? 'ring-2 ring-primary-500' : '' }}">
                            @if($plan->is_popular)
                                <div class="absolute top-0 right-0 -mt-2 -mr-2 px-2 py-0.5 bg-primary-500 text-white text-xs font-bold rounded-full shadow-sm">
                                    Popular
                                </div>
                            @endif
                            <h4 class="font-bold text-surface-900 dark:text-white text-lg">{{ $plan->name }}</h4>
                            <div class="mt-2 flex items-baseline text-2xl font-extrabold text-primary-600 dark:text-primary-400">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                                <span class="ml-1 text-sm font-medium text-surface-500 dark:text-surface-400">/ {{ $plan->billing_cycle ?? 'month' }}</span>
                            </div>
                            
                            @if($plan->description)
                            <p class="mt-3 text-sm text-surface-500 dark:text-surface-400 flex-1">
                                {{ $plan->description }}
                            </p>
                            @else
                            <div class="flex-1"></div>
                            @endif
                            
                            <form action="{{ route('customer.subscriptions.create') }}" method="GET" class="mt-6">
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Subscribe Now
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700 dark:text-yellow-500">
                                    There are currently no active subscription plans available for this product. Check back later.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
