@extends('layouts.admin')

@section('title', 'API Integration Details - Admin Panel')
@section('page-title', $apiIntegration->label)

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $apiIntegration->label }}</h2>
                        <p class="text-sm text-gray-500">{{ $apiIntegration->name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.api-integrations.edit', $apiIntegration) }}" 
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Edit
                    </a>
                    <a href="{{ route('admin.api-integrations.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Status Badges -->
            <div class="flex items-center space-x-3">
                @if($apiIntegration->is_active)
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                @else
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                        Inactive
                    </span>
                @endif
                
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $apiIntegration->category }}
                </span>
                
                @if($apiIntegration->test_status === true)
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Connected
                    </span>
                @elseif($apiIntegration->test_status === false)
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        Failed
                    </span>
                @else
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Not Tested
                    </span>
                @endif
            </div>

            <!-- Description -->
            @if($apiIntegration->description)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Description</h3>
                    <p class="text-gray-900">{{ $apiIntegration->description }}</p>
                </div>
            @endif

            <!-- Credentials -->
            @if($apiIntegration->credentials && count($apiIntegration->credentials) > 0)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Credentials</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        @foreach($apiIntegration->credentials as $key => $value)
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <span class="text-xs font-medium text-gray-500 uppercase">Key</span>
                                    <p class="text-sm font-mono text-gray-900">{{ $key }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-xs font-medium text-gray-500 uppercase">Value</span>
                                    <p class="text-sm font-mono text-gray-900">
                                        <span class="filter-password">{{ str_repeat('*', strlen($value)) }}</span>
                                        <button type="button" onclick="togglePassword(this)" 
                                                class="ml-2 text-indigo-600 hover:text-indigo-900 text-xs">
                                            Show
                                        </button>
                                        <span class="hidden actual-password">{{ $value }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Config -->
            @if($apiIntegration->config && count($apiIntegration->config) > 0)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Configuration</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        @foreach($apiIntegration->config as $key => $value)
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <span class="text-xs font-medium text-gray-500 uppercase">Key</span>
                                    <p class="text-sm font-mono text-gray-900">{{ $key }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-xs font-medium text-gray-500 uppercase">Value</span>
                                    <p class="text-sm font-mono text-gray-900">{{ $value }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Test History -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Last Tested</h3>
                    <p class="text-gray-900">
                        {{ $apiIntegration->tested_at ? $apiIntegration->tested_at->format('d M Y, H:i') : 'Never' }}
                    </p>
                    @if($apiIntegration->tested_at)
                        <p class="text-sm text-gray-500">{{ $apiIntegration->tested_at->diffForHumans() }}</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Last Used</h3>
                    <p class="text-gray-900">
                        {{ $apiIntegration->last_used_at ? $apiIntegration->last_used_at->format('d M Y, H:i') : 'Never' }}
                    </p>
                    @if($apiIntegration->last_used_at)
                        <p class="text-sm text-gray-500">{{ $apiIntegration->last_used_at->diffForHumans() }}</p>
                    @endif
                </div>
            </div>

            @if($apiIntegration->test_message)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Last Test Result</h3>
                    <div class="@if($apiIntegration->test_status) bg-green-50 border-green-200 @else bg-red-50 border-red-200 @endif border rounded-lg p-4">
                        <p class="text-sm {{ $apiIntegration->test_status ? 'text-green-800' : 'text-red-800' }}">
                            {{ $apiIntegration->test_message }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                <div>
                    <p class="text-xs text-gray-500">Created At</p>
                    <p class="text-sm text-gray-900">{{ $apiIntegration->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Updated At</p>
                    <p class="text-sm text-gray-900">{{ $apiIntegration->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="flex justify-between items-center">
                <form action="{{ route('admin.api-integrations.test', $apiIntegration) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Test Connection
                    </button>
                </form>
                
                <form action="{{ route('admin.api-integrations.destroy', $apiIntegration) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this integration? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Integration
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(btn) {
    const span = btn.previousElementSibling;
    const actualPassword = btn.nextElementSibling;
    
    if (span.classList.contains('filter-password')) {
        span.textContent = actualPassword.textContent;
        btn.textContent = 'Hide';
        span.classList.remove('filter-password');
        span.classList.add('actual-password');
    } else {
        span.textContent = '*'.repeat(actualPassword.textContent.length);
        btn.textContent = 'Show';
        span.classList.remove('actual-password');
        span.classList.add('filter-password');
    }
}
</script>
@endpush
@endsection
