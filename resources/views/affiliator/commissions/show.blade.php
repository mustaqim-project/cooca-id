@extends('layouts.affiliator')

@section('title', 'Commission Detail')
@section('subtitle', 'Detailed information for this commission record')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('affiliator.commissions.index') }}" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Commissions
        </a>
    </div>

    <div class="corporate-card">
        <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-surface-900 dark:text-white">Commission Record</h3>
            @php
                $statusClass = match($commission->status) {
                    'cleared', 'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    'pending'         => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'cancelled'       => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                    default           => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300',
                };
            @endphp
            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                {{ ucfirst($commission->status) }}
            </span>
        </div>
        <div class="p-6 space-y-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Commission ID</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white font-mono">{{ $commission->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Date</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white">
                        {{ $commission->created_at->format('d M Y, H:i') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Customer</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $commission->customer?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Commission Level</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $commission->level == 1 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400' }}">
                            Level {{ $commission->level }} ({{ $commission->level == 1 ? 'Direct' : 'Downline' }})
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Transaction / Invoice</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white">
                        {{ $commission->transaction?->invoice_number ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Subscription Plan</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $commission->plan_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Transaction Gross Amount</dt>
                    <dd class="mt-1 text-sm font-semibold text-surface-900 dark:text-white">
                        Rp {{ number_format($commission->gross_amount, 0, ',', '.') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Commission Rate</dt>
                    <dd class="mt-1 text-sm text-surface-900 dark:text-white">{{ $commission->commission_percent }}%</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Commission Amount</dt>
                    <dd class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                        + Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                    </dd>
                </div>
                @if($commission->status === 'pending')
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Available To Withdraw On</dt>
                    <dd class="mt-1 text-sm font-semibold text-yellow-600 dark:text-yellow-400">
                        {{ $commission->created_at->addDays(14)->format('d M Y') }}
                    </dd>
                </div>
                @endif
                @if($commission->cleared_at)
                <div>
                    <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Cleared On</dt>
                    <dd class="mt-1 text-sm font-semibold text-green-600 dark:text-green-400">
                        {{ $commission->cleared_at->format('d M Y, H:i') }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
