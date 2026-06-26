@extends('layouts.admin')

@section('title', 'Settlements Details')
@section('subtitle', 'View specific settlements data.')

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
            <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back
    </a>
    
    <a href="javascript:void(0)" class="inline-flex items-center px-4 py-2 border border-surface-300 dark:border-surface-600 rounded-md font-medium text-xs text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 shadow-sm transition">
        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit
    </a>
</div>

<div class="corporate-card overflow-hidden">
    <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700">
        <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">Information Overview</h3>
        <p class="mt-1 max-w-2xl text-sm text-surface-500 dark:text-surface-400">Detailed breakdown of the record.</p>
    </div>
    <div class="px-6 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700 dark:sm:divide-surface-700">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">ID</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">#0001</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Title</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">Sample Record</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Status</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Description</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">This is a generated placeholder description for the record details view. It represents standard data output.</dd>
            </div>
        </dl>
    </div>
        </div>
    </div>
</div>
@endsection
