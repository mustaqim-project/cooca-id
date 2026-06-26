@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('subtitle', 'Manage articles and content marketing')

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
            
        
                <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">
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
                    <th scope="col" class="table-th">Article</th>
                    <th scope="col" class="table-th">Author</th>
                    <th scope="col" class="table-th">Stats</th>
                    <th scope="col" class="table-th">Status / Date</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($posts as $post)
                <tr class="hover:bg-surface-50 dark:bg-surface-900">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($post->featured_image)
                                <div class="flex-shrink-0 h-12 w-16 bg-surface-200 rounded overflow-hidden mr-4">
                                    <img src="{{ $post->featured_image }}" alt="" class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="flex-shrink-0 h-12 w-16 bg-surface-100 dark:bg-surface-800 rounded flex items-center justify-center text-surface-400 mr-4">
                                    <i data-lucide="image" class="w-4 h-4 text-xl"></i>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.blog.show', $post->id) }}" class="text-sm font-bold text-primary-600 hover:underline line-clamp-1">
                                    {{ $post->title }}
                                </a>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-surface-500 dark:text-surface-400 bg-surface-100 dark:bg-surface-800 px-2 py-0.5 rounded">{{ $post->category ?? 'Uncategorized' }}</span>
                                    @if($post->is_featured)
                                        <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded flex items-center"><i data-lucide="star" class="w-4 h-4"></i> Featured</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xs">
                                {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $post->author->name ?? 'Unknown Author' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-surface-500 dark:text-surface-400 flex flex-col gap-1">
                            <span title="Views"><i data-lucide="eye" class="w-4 h-4"></i> {{ number_format($post->views ?? 0) }}</span>
                            <span title="Comments"><i data-lucide="message-square" class="w-4 h-4"></i> {{ number_format($post->comments_count ?? 0) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="mb-1">
                            @if($post->is_published)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Draft
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-surface-500 dark:text-surface-400">
                            @if($post->is_published)
                                {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}
                            @else
                                Updated {{ $post->updated_at->format('M d, Y') }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.blog.show', $post->id) }}" class="text-primary-600 hover:text-primary-900" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.blog.edit', $post->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            
                            <form class="form-confirm-delete" action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" class="inline form-confirm-delete" >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <p>No blog posts found.</p>
                        @if(empty($filters['search']))
                        <div class="mt-4">
                            <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                                Write your first post <span aria-hidden="true">&rarr;</span>
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
