@extends('layouts.customer')

@section('title', 'Subscribe to Plan')
@section('subtitle', 'Complete your subscription purchase')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form action="{{ route('customer.subscriptions.store') }}" method="POST"  class="form-confirm-submit">
        @csrf
        
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Assuming plan_id is passed via query string -->
            <input type="hidden" name="subscription_plan_id" value="{{ request('plan_id') }}">
            
            <div class="p-6">
                <div class="rounded-md bg-primary-50 dark:bg-primary-900/30 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm text-primary-700 dark:text-primary-300">
                                You are about to subscribe. Please ensure the plan selected matches your needs.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Select Payment Method</label>
                        <select name="payment_method" class="mt-1 block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="bank_transfer">Bank Transfer (Virtual Account)</option>
                            <option value="ewallet">E-Wallet (OVO, GoPay, Dana)</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    
                    @if(request('license_id'))
                    <div>
                        <input type="hidden" name="license_id" value="{{ request('license_id') }}">
                        <p class="text-sm text-surface-500 dark:text-surface-400">This subscription will be attached to your existing license.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700 flex justify-end">
                
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
