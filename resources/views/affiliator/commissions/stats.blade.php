@extends('layouts.affiliator')

@section('title', 'Commission Statistics')
@section('subtitle', 'Analytics of your commission earnings')

@section('content')
<div class="mb-4">
    <a href="{{ route('affiliator.commissions.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Commissions
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Total Earned</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($total_commission ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-white dark:bg-surface-800/20 rounded-full flex items-center justify-center">
                <i data-lucide="wallet" class="w-4 h-4"></i>
            </div>
        </div>
        <p class="mt-4 text-xs text-green-100">All-time earnings from all referrals</p>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-primary-100 text-sm font-medium mb-1">Available to Withdraw</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($cleared_commission ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-white dark:bg-surface-800/20 rounded-full flex items-center justify-center">
                <i data-lucide="coins" class="w-4 h-4"></i>
            </div>
        </div>
        <p class="mt-4 text-xs text-primary-100">Cleared commissions ready for payout</p>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium mb-1">Pending Clearance</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($pending_commission ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-white dark:bg-surface-800/20 rounded-full flex items-center justify-center">
                <i data-lucide="hourglass-split" class="w-4 h-4 text-2xl"></i>
            </div>
        </div>
        <p class="mt-4 text-xs text-yellow-100">Commissions waiting for clearance period</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 p-6">
        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-4">Earnings Breakdown by Product</h3>
        
        @if(isset($breakdown) && count($breakdown) > 0)
            <div class="space-y-4">
                @foreach($breakdown as $item)
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1">
                        <span class="text-surface-700 dark:text-surface-300">{{ $item->product_name ?? 'Unknown' }}</span>
                        <span class="text-surface-900 dark:text-white">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-surface-200 dark:bg-surface-700 rounded-full h-2">
                        @php 
                            $percent = $total_commission > 0 ? ($item->total / $total_commission) * 100 : 0; 
                        @endphp
                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-surface-500 dark:text-surface-400">
                <i data-lucide="pie-chart" class="w-4 h-4 text-4xl mb-3 block text-surface-300 dark:text-surface-600 dark:text-surface-400"></i>
                <p>No breakdown data available yet.</p>
            </div>
        @endif
    </div>
    
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 p-6">
        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-4">Quick Actions</h3>
        
        <div class="space-y-3">
            <a href="{{ route('affiliator.withdrawals.create') }}" class="block w-full text-left px-4 py-4 rounded-lg border border-surface-200 dark:border-surface-700 hover:border-primary-500 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors group">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-primary-500 mr-3">
                        <i data-lucide="bank" class="w-4 h-4 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-surface-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-400">Request Withdrawal</h4>
                        <p class="text-xs text-surface-500 dark:text-surface-400">Transfer available balance to your bank</p>
                    </div>
                    <div class="ml-auto text-surface-400 group-hover:text-primary-500">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('affiliator.withdrawals.index') }}" class="block w-full text-left px-4 py-4 rounded-lg border border-surface-200 dark:border-surface-700 hover:border-primary-500 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors group">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-primary-500 mr-3">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-surface-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-400">Withdrawal History</h4>
                        <p class="text-xs text-surface-500 dark:text-surface-400">View your past payouts</p>
                    </div>
                    <div class="ml-auto text-surface-400 group-hover:text-primary-500">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection