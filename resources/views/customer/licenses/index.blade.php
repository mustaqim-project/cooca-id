@extends('layouts.customer')

@section('title', 'My Licenses')
@section('subtitle', 'Manage and view your product licenses')

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
                    <th scope="col" class="table-th">Product</th>
                    <th scope="col" class="table-th">License Key</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Activated At</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($licenses as $license)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-md bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-500 font-bold">
                                    <i data-lucide="key" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-surface-900 dark:text-white">
                                    {{ $license->product->name ?? 'Unknown Product' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-surface-900 dark:text-white font-mono bg-surface-100 dark:bg-surface-900 px-2 py-1 rounded inline-block">
                            {{ substr($license->license_key, 0, 8) }}...{{ substr($license->license_key, -4) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($license->status == 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                        @elseif($license->status == 'suspended')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Suspended</span>
                        @elseif($license->status == 'expired')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Expired</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200 dark:bg-surface-700 dark:text-surface-300">{{ ucfirst($license->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ $license->activated_at ? \Carbon\Carbon::parse($license->activated_at)->format('M d, Y') : 'Not activated yet' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('customer.licenses.show', $license->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">
                            <i data-lucide="eye" class="w-4 h-4"></i> Details
                        </a>
                        @if($license->status == 'active')
                            <a href="{{ route('customer.licenses.credentials', $license->id) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                <i data-lucide="shield-lock" class="w-4 h-4"></i> API Auth
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-surface-500 dark:text-surface-400">
                        <div class="flex flex-col items-center">
                            <i data-lucide="key" class="w-4 h-4 text-4xl mb-3 text-surface-300 dark:text-surface-600 dark:text-surface-400"></i>
                            <p>You don't have any licenses yet.</p>
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
