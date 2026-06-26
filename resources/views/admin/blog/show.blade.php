@extends('layouts.admin')

@section('title', 'View Post')
@section('subtitle', 'Article details and preview')

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
            <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Articles
    </a>
    
    <div class="flex gap-2">
        <a href="{{ route('admin.blog.edit', $post->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit Post
        </a>
        
        <form class="form-confirm-delete" action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" class="inline" >
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            @if($post->featured_image)
                <div class="w-full h-64 sm:h-80 bg-surface-100 dark:bg-surface-800 overflow-hidden">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                </div>
            @endif
            
            <div class="p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                        {{ $post->category ?? 'Uncategorized' }}
                    </span>
                    @if($post->is_featured)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 flex items-center">
                            <i data-lucide="star" class="w-4 h-4"></i> Featured
                        </span>
                    @endif
                </div>
                
                <h1 class="text-3xl font-extrabold text-surface-900 dark:text-white tracking-tight mb-2">{{ $post->title }}</h1>
                
                <div class="flex items-center text-sm text-surface-500 dark:text-surface-400 mb-8 border-b border-surface-100 pb-6">
                    <div class="flex items-center mr-6">
                        <div class="h-6 w-6 rounded-full bg-surface-200 flex items-center justify-center text-surface-600 dark:text-surface-400 font-bold text-xs mr-2">
                            {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                        </div>
                        <span>{{ $post->author->name ?? 'Unknown Author' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                
                @if($post->excerpt)
                    <div class="text-lg text-surface-600 dark:text-surface-400 italic mb-8 border-l-4 border-primary-500 pl-4">
                        {{ $post->excerpt }}
                    </div>
                @endif
                
                <div class="prose prose-indigo max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Status Card -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Publishing Status</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-surface-500 dark:text-surface-400">Status</dt>
                        <dd class="text-sm font-medium">
                            @if($post->is_published)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Draft
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="flex justify-between items-center border-t border-surface-100 pt-4">
                        <dt class="text-sm text-surface-500 dark:text-surface-400">Visibility</dt>
                        <dd class="text-sm font-medium text-surface-900 dark:text-white">
                            {{ $post->is_published ? 'Public' : 'Hidden' }}
                        </dd>
                    </div>
                    
                    @if($post->published_at)
                    <div class="flex justify-between items-center border-t border-surface-100 pt-4">
                        <dt class="text-sm text-surface-500 dark:text-surface-400">Published On</dt>
                        <dd class="text-sm font-medium text-surface-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y H:i') }}
                        </dd>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center border-t border-surface-100 pt-4">
                        <dt class="text-sm text-surface-500 dark:text-surface-400">Created At</dt>
                        <dd class="text-sm font-medium text-surface-900 dark:text-white">
                            {{ $post->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                    
                    <div class="flex justify-between items-center border-t border-surface-100 pt-4">
                        <dt class="text-sm text-surface-500 dark:text-surface-400">Last Updated</dt>
                        <dd class="text-sm font-medium text-surface-900 dark:text-white">
                            {{ $post->updated_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- SEO Info -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">SEO & Metadata</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h4 class="text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">URL Slug</h4>
                    <a href="{{ url('/blog/' . $post->slug) }}" target="_blank" class="text-sm text-primary-600 hover:underline break-all">
                        {{ $post->slug }} <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Meta Title</h4>
                    <p class="text-sm text-surface-900 dark:text-white">{{ $post->meta_title ?: 'Not set (defaults to title)' }}</p>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Meta Description</h4>
                    <p class="text-sm text-surface-900 dark:text-white">{{ $post->meta_description ?: 'Not set (defaults to excerpt)' }}</p>
                </div>
                
                <div>
                    <h4 class="text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $tags = is_string($post->tags) ? json_decode($post->tags, true) : ($post->tags ?? []);
                        @endphp
                        
                        @forelse($tags as $tag)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200">
                                {{ $tag }}
                            </span>
                        @empty
                            <span class="text-sm text-surface-500 dark:text-surface-400 italic">No tags</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Engagement Stats -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Engagement Stats</h3>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-surface-50 dark:bg-surface-900 rounded-lg border border-surface-100">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    <div class="text-xl font-bold text-surface-900 dark:text-white">{{ number_format($post->views ?? 0) }}</div>
                    <div class="text-xs text-surface-500 dark:text-surface-400 uppercase">Views</div>
                </div>
                <div class="text-center p-3 bg-surface-50 dark:bg-surface-900 rounded-lg border border-surface-100">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    <div class="text-xl font-bold text-surface-900 dark:text-white">{{ number_format($post->comments_count ?? 0) }}</div>
                    <div class="text-xs text-surface-500 dark:text-surface-400 uppercase">Comments</div>
                </div>
            </div>
        </div>
        
    </div>
        </div>
    </div>
</div>
@endsection
