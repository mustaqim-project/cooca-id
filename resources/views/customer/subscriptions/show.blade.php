@extends('layouts.customer')

@section('title', 'Subscription Details')
@section('subtitle', 'View and manage your subscription')

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
            <a href="{{ route('customer.subscriptions.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Subscriptions
    </a>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900/50">
        <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
            Subscription Information
        </h3>
        <div>
            @if($subscription->is_active)
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-1.5 mt-0.5"></i> Active
                </span>
            @elseif($subscription->is_cancelled)
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                    <i data-lucide="x-circle" class="w-4 h-4 mr-1.5 mt-0.5"></i> Cancelled
                </span>
            @elseif($subscription->expires_at && $subscription->expires_at->isPast())
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                    <i data-lucide="alert-triangle" class="w-4 h-4 mr-1.5 mt-0.5"></i> Expired
                </span>
            @else
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200 dark:bg-surface-700 dark:text-surface-300">
                    <i data-lucide="hourglass-split" class="w-4 h-4 mr-1.5 mt-0.5"></i> Pending
                </span>
            @endif
        </div>
    </div>
    
    <div class="px-6 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700 dark:sm:divide-surface-700">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Product</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-semibold">
                    {{ $subscription->plan->product->name ?? 'N/A' }}
                </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Plan</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ $subscription->plan->name ?? 'N/A' }}
                </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Subscription ID</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                    {{ $subscription->id }}
                </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Started At</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ $subscription->started_at ? $subscription->started_at->format('F d, Y - H:i') : '-' }}
                </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Expires At</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ $subscription->expires_at ? $subscription->expires_at->format('F d, Y - H:i') : 'Lifetime' }}
                    @if($subscription->expires_at && $subscription->is_active && $subscription->expires_at->isFuture())
                        <span class="text-surface-500 dark:text-surface-400 text-xs ml-2">
                            ({{ $subscription->expires_at->diffForHumans() }})
                        </span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>
    
    <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
        @if($subscription->is_active)
            <button type="button" onclick="renewSubscription('{{ $subscription->id }}')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i data-lucide="repeat" class="w-4 h-4"></i> Renew
            </button>
            <button type="button" onclick="cancelSubscription('{{ $subscription->id }}')" class="inline-flex justify-center py-2 px-4 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-red-700 dark:text-red-400 bg-white dark:bg-surface-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i data-lucide="x" class="w-4 h-4"></i> Cancel Subscription
            </button>
        @else
            <a href="{{ route('customer.products.show', $subscription->plan->product->slug ?? '') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Subscribe Again
            </a>
        @endif
    </div>
        </div>
    </div>
</div>
@endsection
