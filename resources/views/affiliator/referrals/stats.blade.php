@extends('layouts.affiliator')

@section('title', 'Referral Statistics')
@section('subtitle', 'Analytics and performance of your referral campaigns')

@section('content')
<div class="mb-4">
    <a href="{{ route('affiliator.referrals.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Referrals
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up rounded-lg shadow-sm border border-surface-200 dark:border-surface-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900/30 rounded-md p-3">
                <i data-lucide="users" class="w-4 h-4 text-primary-600 dark:text-primary-400 text-xl"></i>
            </div>
            <div class="ml-4 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Total Referrals</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-surface-900 dark:text-white">
                            <!-- Placeholder data, usually passed from controller -->
                            {{ auth()->guard('affiliator')->user()->customers()->count() }}
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up rounded-lg shadow-sm border border-surface-200 dark:border-surface-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-md p-3">
                <i data-lucide="cart-check" class="w-4 h-4 text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <div class="ml-4 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Active Subscriptions</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-surface-900 dark:text-white">
                            <!-- Needs actual stats from backend -->
                            0
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up rounded-lg shadow-sm border border-surface-200 dark:border-surface-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-md p-3">
                <i data-lucide="coins" class="w-4 h-4 text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <div class="ml-4 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Total Earned</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-surface-900 dark:text-white">
                            Rp 0
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up rounded-lg shadow-sm border border-surface-200 dark:border-surface-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-md p-3">
                <i data-lucide="graph-up-arrow" class="w-4 h-4 text-yellow-600 dark:text-yellow-400 text-xl icon-3d"></i>
            </div>
            <div class="ml-4 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Conversion Rate</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-surface-900 dark:text-white">
                            0%
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 p-6 text-center">
    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-surface-100 dark:bg-surface-700 mb-4">
        <i data-lucide="bar-chart-line" class="w-4 h-4 text-3xl text-surface-400 dark:text-surface-500 dark:text-surface-400"></i>
    </div>
    <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Detailed Analytics Coming Soon</h3>
    <p class="text-surface-500 dark:text-surface-400 max-w-md mx-auto">
        We are working on bringing you more detailed charts and historical data to help you track your referral performance over time.
    </p>
</div>
@endsection