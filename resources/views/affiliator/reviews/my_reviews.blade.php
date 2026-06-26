@extends('layouts.affiliator')

@section('title', 'Reviews')
@section('subtitle', 'Manage your reviews data.')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-bold text-surface-900 dark:text-white">Reviews</h2>
    <a href="javascript:void(0)" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add New
    </a>
</div>

<div class="corporate-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
            <thead class="bg-surface-50 dark:bg-surface-800/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Name / Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($reviews ?? [] as $review)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $review->title ?? 'No Title' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $review->product->name ?? 'Unknown Product' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($review->status ?? 'pending') {
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($review->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->created_at ? $review->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <span class="text-surface-400">Read Only</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">You haven't written any reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
        
    </div>
</div>
@endsection
