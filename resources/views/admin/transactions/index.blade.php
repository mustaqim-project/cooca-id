@extends('layouts.admin')

@section('title', 'Transactions Management')
@section('subtitle', 'View and manage all financial transactions')

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
                    <th scope="col" class="table-th">Invoice #</th>
                    <th scope="col" class="table-th">Customer</th>
                    <th scope="col" class="table-th">Amount</th>
                    <th scope="col" class="table-th">Payment Method</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Date</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($transactions as $transaction)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
                        {{ $transaction->invoice_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $transaction->customer->name ?? 'Unknown Customer' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $transaction->customer->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
                        @if($transaction->net_amount != $transaction->gross_amount)
                            <div class="text-xs text-surface-500 dark:text-surface-400">Net: Rp {{ number_format($transaction->net_amount, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-surface-900 dark:text-white">{{ strtoupper($transaction->payment_type ?? '-') }}</div>
                        <div class="text-xs text-surface-500 dark:text-surface-400">{{ $transaction->bank ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($transaction->status) {
                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                'refunded' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ $transaction->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="viewTransaction({{ $transaction->id }})" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">
                            <i data-lucide="eye" class="w-4 h-4"></i> Details
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-surface-500 dark:text-surface-400">
                        <div class="flex flex-col items-center">
                            <i data-lucide="receipt" class="w-4 h-4 text-4xl mb-3 text-surface-300 dark:text-surface-600 dark:text-surface-400"></i>
                            <p>No transactions found.</p>
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
