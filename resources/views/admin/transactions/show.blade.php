@extends('layouts.admin')

@section('title', 'Transaction Details')
@section('subtitle', 'View complete details for transaction ' . $transaction->id)

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
            <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Transactions
    </a>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900">
        <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
            Transaction #{{ $transaction->id }}
        </h3>
        <div>
            @php
                $statusClass = match($transaction->status) {
                    'paid', 'settlement', 'capture' => 'bg-green-100 text-green-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'failed', 'deny', 'cancel', 'expire' => 'bg-red-100 text-red-800',
                    'refunded' => 'bg-blue-100 text-blue-800',
                    default => 'bg-surface-100 text-surface-800'
                };
            @endphp
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $statusClass }}">
                {{ strtoupper($transaction->status) }}
            </span>
        </div>
    </div>
    
    <div class="px-6 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Customer</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <a href="{{ route('admin.customers.show', $transaction->customer_id ?? 0) }}" class="text-primary-600 hover:underline">
                        {{ $transaction->customer->name ?? 'Unknown Customer' }}
                    </a>
                    <span class="text-surface-500 dark:text-surface-400 ml-2">({{ $transaction->customer->email ?? '' }})</span>
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Order ID / Invoice Number</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                    {{ $transaction->invoice_number ?? '-' }}
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Gross Amount</dt>
                <dd class="mt-1 text-lg font-bold text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Payment Method</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ strtoupper($transaction->payment_type ?? 'WAITING') }}
                    @if($transaction->bank)
                        <span class="text-surface-500 dark:text-surface-400">({{ $transaction->bank }})</span>
                    @endif
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Subscription Link</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    @if($transaction->subscription_id)
                        <a href="{{ route('admin.subscriptions.show', $transaction->subscription_id) }}" class="text-primary-600 hover:underline">
                            View Subscription #{{ $transaction->subscription_id }}
                        </a>
                    @else
                        <span class="text-surface-500 dark:text-surface-400">No linked subscription</span>
                    @endif
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y - H:i:s') }}
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Last Updated</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ \Carbon\Carbon::parse($transaction->updated_at)->format('F d, Y - H:i:s') }}
                </dd>
            </div>
            
            @if($transaction->payment_url)
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Payment Link</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <a href="{{ $transaction->payment_url }}" target="_blank" class="text-primary-600 hover:underline break-all">
                        {{ $transaction->payment_url }}
                    </a>
                </dd>
            </div>
            @endif
        </dl>
    </div>
    
    <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
        <!-- Actions based on status -->
        @if($transaction->status == 'pending')
            <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none" onclick="markAsPaid({{ $transaction->id }})">
                <i data-lucide="check" class="w-4 h-4"></i> Mark as Paid
            </button>
        @endif
        
        @if(in_array($transaction->status, ['paid', 'settlement']))
            <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none" onclick="refund({{ $transaction->id }})">
                <i data-lucide="arrow-counterclockwise" class="w-4 h-4 mr-2"></i> Refund
            </button>
        @endif
    </div>
        </div>
    </div>
</div>
@endsection
