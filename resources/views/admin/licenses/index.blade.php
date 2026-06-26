@extends('layouts.admin')

@section('title', 'Licenses')
@section('subtitle', 'Manage software licenses and domain authorizations')

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
                    <th scope="col" class="table-th">License Key / Domain</th>
                    <th scope="col" class="table-th">Customer</th>
                    <th scope="col" class="table-th">Product</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($licenses as $license)
                <tr class="hover:bg-surface-50 dark:bg-surface-900">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white font-mono" title="{{ $license->key }}">
                            {{ substr($license->key, 0, 16) }}...
                        </div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">
                            {{ $license->domain ?? 'Unconfigured' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.customers.show', $license->customer_id ?? 0) }}" class="text-sm font-medium text-primary-600 hover:underline">
                            {{ $license->customer->name ?? 'Unknown Customer' }}
                        </a>
                        <div class="text-xs text-surface-500 dark:text-surface-400">{{ $license->customer->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-surface-900 dark:text-white">{{ $license->product->name ?? 'Unknown Product' }}</div>
                        @if($license->subscription_id)
                            <a href="{{ route('admin.subscriptions.show', $license->subscription_id) }}" class="text-xs text-primary-600 hover:underline">
                                Sub: #{{ substr($license->subscription_id, 0, 8) }}
                            </a>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($license->status) {
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-yellow-100 text-yellow-800',
                                'revoked' => 'bg-red-100 text-red-800',
                                'expired' => 'bg-surface-100 text-surface-800',
                                default => 'bg-surface-100 text-surface-800'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($license->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            @if($license->status == 'active')
                                <button type="button" class="text-red-600 hover:text-red-900" title="Revoke License" onclick="revokeLicense('{{ $license->id }}')">
                                    <i data-lucide="x" class="w-4 h-4"></i> Revoke
                                </button>
                            @else
                                <button type="button" class="text-green-600 hover:text-green-900" title="Activate License" onclick="activateLicense('{{ $license->id }}')">
                                    <i data-lucide="check" class="w-4 h-4"></i> Activate
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <i data-lucide="key" class="w-4 h-4"></i>
                        <p>No licenses found.</p>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
