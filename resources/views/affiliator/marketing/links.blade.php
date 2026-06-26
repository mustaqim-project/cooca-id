@extends('layouts.affiliator')

@section('title', 'Marketing')
@section('subtitle', 'Manage your marketing data.')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-bold text-surface-900 dark:text-white">Marketing</h2>
    
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
                @forelse($links ?? [] as $idx => $link)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $idx + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $link['name'] }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $link['description'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Ready</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end">
                        <button onclick="navigator.clipboard.writeText('{{ addslashes($link['url']) }}'); alert('Link copied to clipboard!')" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 inline-flex items-center" title="Copy Link"><i data-lucide="copy" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No links found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
        
    </div>
</div>
@endsection
