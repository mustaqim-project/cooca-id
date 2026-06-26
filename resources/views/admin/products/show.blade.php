@extends('layouts.admin')

@section('title', 'Product Details')
@section('subtitle', 'View and manage ' . $product->name)

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
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Products
    </a>
    
    <div class="flex gap-2">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 border border-surface-300 dark:border-surface-600 rounded-md shadow-sm text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 focus:outline-none">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit Product
        </a>
        
        <form class="form-confirm-delete" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" >
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Product Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Details -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
                    General Information
                </h3>
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive / Draft' }}
                </span>
            </div>
            
            <div class="px-6 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Product Name</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white font-bold sm:mt-0 sm:col-span-2">
                            {{ $product->name }}
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Description</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $product->description }}</dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Category</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- Integration Details -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
                    Technical Integration
                </h3>
            </div>
            
            <div class="px-6 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Product ID (UUID)</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                            {{ $product->id }}
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Webhook URL</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 break-all">
                            {{ $product->webhook_url ?? 'Not configured' }}
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $product->created_at->format('F d, Y H:i') }}
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $product->updated_at->format('F d, Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- Right Column: Stats & Pricing -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Pricing Info -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow text-white overflow-hidden">
            <div class="p-6">
                <p class="text-primary-100 text-sm font-medium uppercase tracking-wide">Pricing</p>
                <div class="mt-2 flex items-baseline text-4xl font-extrabold">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                <p class="mt-1 text-primary-100 text-sm">
                    {{ ucfirst($product->type ?? 'One-time') }} 
                    @if(($product->type ?? '') == 'subscription') 
                        / billing cycle
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">
                    Product Performance
                </h3>
            </div>
            
            <div class="divide-y divide-surface-200 dark:divide-surface-700">
                <div class="p-4 flex justify-between items-center">
                    <span class="text-sm text-surface-500 dark:text-surface-400">Total Sales</span>
                    <span class="text-base font-semibold text-surface-900 dark:text-white">{{ $product->transactions()->where('status', 'paid')->count() ?? 0 }}</span>
                </div>
                
                <div class="p-4 flex justify-between items-center">
                    <span class="text-sm text-surface-500 dark:text-surface-400">Active Licenses</span>
                    <span class="text-base font-semibold text-surface-900 dark:text-white">{{ $product->licenses()->where('status', 'active')->count() ?? 0 }}</span>
                </div>
                
                <div class="p-4 flex justify-between items-center">
                    <span class="text-sm text-surface-500 dark:text-surface-400">Total Revenue</span>
                    <span class="text-base font-semibold text-green-600">
                        Rp {{ number_format($product->transactions()->where('status', 'paid')->sum('gross_amount') ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            
            <div class="p-4 bg-surface-50 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700">
                <a href="{{ route('admin.transactions.index', ['product_id' => $product->id]) }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium block text-center">
                    View all transactions &rarr;
                </a>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
