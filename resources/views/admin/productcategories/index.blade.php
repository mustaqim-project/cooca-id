@extends('layouts.admin')

@section('title', 'Product Categories')
@section('subtitle', 'Manage product groupings and taxonomies')

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
            
        
                <a href="{{ route('admin.product-categories.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">
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
                    <th scope="col" class="table-th">Icon</th>
                    <th scope="col" class="table-th">Name</th>
                    <th scope="col" class="table-th">Products</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($categories as $category)
                <tr class="hover:bg-surface-50 dark:bg-surface-900">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($category->icon)
                            <i class="{{ $category->icon }} text-xl text-surface-600 dark:text-surface-400"></i>
                        @else
                            <div class="h-8 w-8 rounded bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-500 dark:text-surface-400 text-xs font-medium mx-auto">
                                {{ strtoupper(substr($category->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $category->name }}</div>
                        <div class="text-xs text-surface-500 dark:text-surface-400 font-mono">{{ $category->slug }}</div>
                        @if($category->description)
                            <div class="text-xs text-surface-500 dark:text-surface-400 mt-1 line-clamp-1">{{ Str::limit($category->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                            {{ $category->products_count ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.product-categories.show', $category->id) }}" class="text-primary-600 hover:text-primary-900" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.product-categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            
                            @if(($category->products_count ?? 0) === 0)
                            <form class="form-confirm-delete" action="{{ route('admin.product-categories.destroy', $category->id) }}" method="POST" class="inline form-confirm-delete" >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @else
                            <button type="button" class="text-surface-400 cursor-not-allowed" title="Cannot delete category with products">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <i data-lucide="tags" class="w-4 h-4"></i>
                        <p>No categories found.</p>
                        @if(empty($filters['search']) && empty($filters['status']))
                        <div class="mt-4">
                            <a href="{{ route('admin.product-categories.create') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                                Create your first category <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
