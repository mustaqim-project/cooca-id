@extends('layouts.affiliator')

@section('title', 'Withdrawal Details')
@section('subtitle', 'View details for payout request #' . $withdrawal->id)

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
            <a href="{{ route('affiliator.withdrawals.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Withdrawals
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900/50">
            <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
                Payout Status
            </h3>
            <div>
                @php
                    $statusClass = match($withdrawal->status) {
                        'completed', 'paid', 'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'rejected', 'failed', 'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                        default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                    };
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $statusClass }}">
                    {{ strtoupper($withdrawal->status) }}
                </span>
            </div>
        </div>
        
        <div class="px-6 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700 dark:sm:divide-surface-700">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Request ID</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                        {{ $withdrawal->id }}
                    </dd>
                </div>
                
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Amount Requested</dt>
                    <dd class="mt-1 text-lg font-bold text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </dd>
                </div>
                
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900/30">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Destination Account</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <div class="mr-3 text-primary-500"><i data-lucide="bank" class="w-4 h-4 text-xl"></i></div>
                            <div>
                                <p class="font-medium">{{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }} - {{ $withdrawal->account_number }}</p>
                                <p class="text-surface-500 dark:text-surface-400">A/N: {{ $withdrawal->account_name }}</p>
                            </div>
                        </div>
                    </dd>
                </div>
                
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Date Requested</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('F d, Y - H:i:s') }}
                    </dd>
                </div>
                
                @if($withdrawal->processed_at)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Date Processed</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('F d, Y - H:i:s') }}
                    </dd>
                </div>
                @endif
                
                @if($withdrawal->notes)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900/30">
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Admin Notes</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $withdrawal->notes }}
                    </dd>
                </div>
                @endif
                
                @if($withdrawal->status == 'rejected')
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-red-50 dark:bg-red-900/20">
                    <dt class="text-sm font-medium text-red-700 dark:text-red-400">Rejection Reason</dt>
                    <dd class="mt-1 text-sm text-red-800 dark:text-red-300 sm:mt-0 sm:col-span-2 font-medium">
                        {{ $withdrawal->reject_reason ?? 'No specific reason provided.' }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
        
    </div>
        </div>
    </div>
</div>
@endsection
