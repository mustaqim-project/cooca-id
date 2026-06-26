@extends('layouts.admin')

@section('title', 'Pages')
@section('subtitle', 'Manage your pages data.')

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
                <a href="{{ route('admin.cms.pages.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">
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
                    <th class="table-th">ID</th>
                    <th class="table-th">Name / Title</th>
                    <th class="table-th">Status</th>
                    <th class="table-th">Date</th>
                    <th class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($pages ?? [] as $page)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $page->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $page->title }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">/{{ $page->slug }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $page->created_at ? $page->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.cms.pages.edit', $page->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">Edit</a>
                        <form class="form-confirm-delete inline" action="{{ route('admin.cms.pages.destroy', $page->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No pages found.</td></tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
