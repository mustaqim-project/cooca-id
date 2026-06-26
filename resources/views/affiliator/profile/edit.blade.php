@extends('layouts.affiliator')

@section('title', 'Edit Profile')
@section('subtitle', 'Modify profile record.')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form action="#" method="POST"  class="form-confirm-submit">
        @csrf
        
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Title / Name</label>
                <input type="text" name="name" class="w-full rounded-md border-surface-300 dark:border-surface-600 dark:bg-surface-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white" placeholder="Enter title">
            </div>
            
            <div class="col-span-2">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-md border-surface-300 dark:border-surface-600 dark:bg-surface-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white" placeholder="Enter description"></textarea>
            </div>
            
            <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-surface-300 dark:border-surface-600 dark:bg-surface-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <div class="corporate-card">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting.</p>
                        
                        <div class="flex flex-col space-y-3">
                            
                            <a href="javascript:history.back()" class="btn btn-secondary w-full">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
