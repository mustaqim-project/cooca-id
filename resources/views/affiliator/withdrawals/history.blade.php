@extends('layouts.affiliator')

@section('title', 'Withdrawal History')
@section('subtitle', 'View all your past and pending payout requests')

@section('content')
<div class="mb-4">
    <a href="{{ route('affiliator.withdrawals.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Withdrawals
    </a>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
    <div class="p-4 border-b border-surface-200 dark:border-surface-700">
        <h3 class="text-lg font-medium text-surface-900 dark:text-white">All Payouts</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
            <thead class="bg-surface-50 dark:bg-surface-900/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Date Requested</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Method</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Details</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-surface-800 animate-fade-in-up divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-900 dark:text-white font-medium">
                        {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y H:i') }}
                        @if($withdrawal->processed_at)
                            <div class="text-xs text-surface-500 dark:text-surface-400 font-normal">Processed: {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('M d, Y') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-900 dark:text-white font-bold">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }}
                        @if($withdrawal->account_number)
                            <div class="text-xs">***{{ substr($withdrawal->account_number, -4) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($withdrawal->status) {
                                'completed', 'paid', 'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rejected', 'failed', 'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('affiliator.withdrawals.show', $withdrawal->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">
                            View <i data-lucide="chevron-right" class="w-4 h-4 text-xs ml-1"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <div class="flex flex-col items-center">
                            <i data-lucide="calendar-x" class="w-4 h-4 text-5xl mb-4 text-surface-300 dark:text-surface-600 dark:text-surface-400"></i>
                            <h3 class="text-lg font-medium text-surface-900 dark:text-white">No history found</h3>
                            <p class="mt-1">You haven't made any withdrawal requests yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($withdrawals, 'hasPages') && $withdrawals->hasPages())
    <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
        {{ $withdrawals->links() }}
    </div>
    @endif
</div>
@endsection