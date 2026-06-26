@extends('layouts.affiliator')

@section('title', 'My Withdrawals')
@section('subtitle', 'Manage your payout requests and view your balance')

@section('content')
<div class="space-y-6">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-surface-400"></i>
            </div>
            <input type="text" placeholder="Search..." class="block w-full pl-10 pr-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800 text-surface-900 dark:text-white placeholder-surface-400 shadow-sm transition-shadow hover:shadow-md">
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            
        </div>
    </div>

    <!-- Data Table -->
    <div class="corporate-card">
        <div class="overflow-x-auto">
            <table class="corporate-table">
                <thead class="table-thead">
                    
                    
                    
                <tr>
                    <th scope="col" class="table-th">Date</th>
                    <th scope="col" class="table-th">Amount</th>
                    <th scope="col" class="table-th">Method</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
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
                            Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <div class="flex flex-col items-center">
                            <i data-lucide="bank" class="w-4 h-4 text-5xl mb-4 text-surface-300 dark:text-surface-600 dark:text-surface-400"></i>
                            <h3 class="text-lg font-medium text-surface-900 dark:text-white">No withdrawals yet</h3>
                            <p class="mt-1">When you request a payout, it will appear here.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
