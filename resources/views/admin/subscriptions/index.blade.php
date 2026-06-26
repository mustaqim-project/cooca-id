@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('subtitle', 'Manage customer subscriptions')

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
                    <th scope="col" class="table-th">Customer</th>
                    <th scope="col" class="table-th">Product</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Expires At</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($subscriptions as $subscription)
                <tr class="hover:bg-surface-50 dark:bg-surface-900">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                    {{ substr($subscription->customer->name ?? '?', 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-surface-900 dark:text-white">
                                    <a href="{{ route('admin.customers.show', $subscription->customer_id ?? 0) }}" class="hover:underline hover:text-primary-600">
                                        {{ $subscription->customer->name ?? 'Unknown Customer' }}
                                    </a>
                                </div>
                                <div class="text-sm text-surface-500 dark:text-surface-400">{{ $subscription->customer->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-surface-900 dark:text-white font-medium">{{ $subscription->product->name ?? 'Unknown Product' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">ID: {{ substr($subscription->id, 0, 8) }}...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($subscription->status) {
                                'active' => 'bg-green-100 text-green-800',
                                'trial' => 'bg-blue-100 text-blue-800',
                                'expired' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-surface-100 text-surface-800',
                                default => 'bg-surface-100 text-surface-800'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ $subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('M d, Y') : 'Lifetime' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="text-primary-600 hover:text-primary-900">
                            Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <i data-lucide="card-checklist" class="w-4 h-4 text-4xl mb-4 block text-surface-400"></i>
                        <p>No subscriptions found matching the criteria.</p>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
