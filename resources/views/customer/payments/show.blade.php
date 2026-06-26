@extends('layouts.customer')

@section('title', 'Payment Details')
@section('subtitle', 'View details for transaction ' . ($payment->invoice_number ?? $payment->id)

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
            <a href="{{ route('customer.payments.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Payments
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900/50">
            <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
                Transaction Status
            </h3>
            <div>
                @php
                    $statusClass = match($payment->status) {
                        'paid', 'settlement', 'capture' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'failed', 'deny', 'cancel', 'expire' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                        'refunded' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                        default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                    };
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $statusClass }}">
                    {{ strtoupper($payment->status) }}
                </span>
            </div>
        </div>
        
        <div class="px-6 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700 dark:sm:divide-surface-700">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Transaction ID</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                        {{ $payment->id }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Order ID / Invoice</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                        {{ $payment->invoice_number ?? '-' }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Gross Amount</dt>
                    <dd class="mt-1 text-lg font-bold text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Payment Method</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ strtoupper($payment->payment_type ?? 'Waiting for payment') }}
                        @if($payment->bank)
                            <span class="text-surface-500 dark:text-surface-400">({{ $payment->bank }})</span>
                        @endif
                    </dd>
                </div>
                @if($payment->payment_url && $payment->status == 'pending')
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Payment Link</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <a href="{{ $payment->payment_url }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline break-all">
                            {{ $payment->payment_url }}
                        </a>
                    </dd>
                </div>
                @endif
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $payment->created_at->format('F d, Y - H:i:s') }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Last Updated</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $payment->updated_at->format('F d, Y - H:i:s') }}
                    </dd>
                </div>
            </dl>
        </div>
        
        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
            @if($payment->status == 'pending' && $payment->payment_url)
                <a href="{{ $payment->payment_url }}" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i> Complete Payment
                </a>
            @endif
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
