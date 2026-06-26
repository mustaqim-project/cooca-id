@extends('layouts.admin')

@section('title', 'Affiliator Details')
@section('subtitle', $affiliator->name)

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
            <nav class="flex text-sm text-surface-500 dark:text-surface-400 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.affiliators.index') }}" class="inline-flex items-center hover:text-primary-600 dark:hover:text-primary-400">
                    Affiliators
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-1"></i>
                    <span class="text-surface-700 dark:text-surface-300">{{ $affiliator->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-surface-900 dark:text-white flex items-center">
            <i data-lucide="user-circle" class="w-8 h-8 mr-3 text-primary-500 icon-3d"></i>
            {{ $affiliator->name }}
        </h2>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.affiliators.edit', $affiliator->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit
            </a>
            <form class="form-confirm-delete m-0 p-0" action="{{ route('admin.affiliators.destroy', $affiliator->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Delete
                </button>
            </form>
            <a href="{{ route('admin.affiliators.index') }}" class="inline-flex items-center px-4 py-2 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Affiliator Info Card -->
    <div class="lg:col-span-1 space-y-6">
        <div class="corporate-card p-6 text-center animate-fade-in-up">
            <div class="w-24 h-24 mx-auto bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center text-3xl font-bold mb-4">
                {{ substr($affiliator->name, 0, 2) }}
            </div>
            <h4 class="text-xl font-bold text-surface-900 dark:text-white mb-1">{{ $affiliator->name }}</h4>
            <p class="text-surface-500 dark:text-surface-400 mb-4">{{ $affiliator->email }}</p>
            
            <div class="mb-4">
                @if($affiliator->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                @endif
            </div>
            
            <div class="mt-6 text-left">
                <p class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Referral Code:</p>
                <code class="block bg-surface-100 dark:bg-surface-700 p-3 rounded text-surface-900 dark:text-white text-center text-lg">{{ $affiliator->referral_code }}</code>
            </div>
            
            <div class="mt-6 text-left border-t border-surface-200 dark:border-surface-700 pt-4">
                <p class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Joined Date:</p>
                <p class="text-surface-900 dark:text-white">{{ \Carbon\Carbon::parse($affiliator->created_at)->format('d F Y') }}</p>
            </div>
        </div>
        
        <div class="corporate-card animate-fade-in-up" style="animation-delay: 100ms;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h5 class="text-lg font-semibold text-surface-900 dark:text-white flex items-center">
                    <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-primary-500"></i> Statistics
                </h5>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Commission</label>
                    <h4 class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}</h4>
                </div>
                <div>
                    <label class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Downlines</label>
                    <h4 class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ count($downlines) }}</h4>
                </div>
                <div>
                    <label class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Withdrawals</label>
                    <h4 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">Rp {{ number_format($affiliator->total_withdrawn ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Downlines Table -->
    <div class="lg:col-span-2">
        <div class="corporate-card animate-fade-in-up" style="animation-delay: 200ms;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center">
                <h5 class="mb-0 text-lg font-semibold text-surface-900 dark:text-white flex items-center">
                    <i data-lucide="network" class="w-5 h-5 mr-2 text-primary-500"></i> Downlines
                </h5>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                    {{ count($downlines) }} Users
                </span>
            </div>
            <div class="overflow-x-auto">
                @if(count($downlines) > 0)
                <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                    <thead class="bg-surface-50 dark:bg-surface-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Referral Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                        @foreach($downlines as $downline)
                        <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                {{ $downline->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs">
                                        {{ substr($downline->name, 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $downline->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                {{ $downline->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <code class="px-2 py-1 bg-surface-100 dark:bg-surface-700 rounded text-surface-800 dark:text-surface-200">{{ $downline->referral_code }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($downline->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                {{ \Carbon\Carbon::parse($downline->created_at)->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-12">
                    <i data-lucide="users" class="w-12 h-12 text-surface-300 dark:text-surface-600 dark:text-surface-400 mx-auto mb-4 icon-3d"></i>
                    <p class="text-surface-500 dark:text-surface-400 text-lg">No downlines found</p>
                </div>
                @endif
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
