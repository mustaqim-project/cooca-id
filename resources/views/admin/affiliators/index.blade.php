@extends('layouts.admin')

@section('title', 'Manage Affiliators')
@section('subtitle', 'Affiliators Management')

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
            <a href="{{ route('admin.affiliators.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add New
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="corporate-card">
        <div class="overflow-x-auto">
            <table class="corporate-table">
                <thead class="table-thead">
                    
                    
                    
                <tr>
                    <th scope="col" class="table-th">ID</th>
                    <th scope="col" class="table-th">Name</th>
                    <th scope="col" class="table-th">Email</th>
                    <th scope="col" class="table-th">Referral Code</th>
                    <th scope="col" class="table-th">Total Downlines</th>
                    <th scope="col" class="table-th">Total Commission</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Joined Date</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($affiliators as $affiliator)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ $affiliator->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-sm">
                                {{ substr($affiliator->name, 0, 2) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $affiliator->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ $affiliator->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <code class="px-2 py-1 bg-surface-100 dark:bg-surface-700 rounded text-surface-800 dark:text-surface-200">{{ $affiliator->referral_code }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ count($affiliator->downlines ?? []) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-900 dark:text-white font-medium">
                        Rp {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($affiliator->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ \Carbon\Carbon::parse($affiliator->created_at)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.affiliators.show', $affiliator->id) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300" title="View Details">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <a href="{{ route('admin.affiliators.edit', $affiliator->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </a>
                            <button onclick="deleteAffiliator({{ $affiliator->id }}, '{{ $affiliator->name }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <i data-lucide="users" class="w-12 h-12 text-surface-300 dark:text-surface-600 dark:text-surface-400 mx-auto mb-4 icon-3d"></i>
                        <p class="text-surface-500 dark:text-surface-400 text-lg">No affiliators found</p>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
