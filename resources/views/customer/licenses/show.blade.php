@extends('layouts.customer')

@section('title', 'License Details')
@section('subtitle', 'View your license information and status')

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
            <a href="{{ route('customer.licenses.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Licenses
    </a>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8 max-w-4xl mx-auto">
    <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900/50">
        <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
            License Information
        </h3>
        <div>
            @if($license->status == 'active')
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-1.5 mt-0.5"></i> Active
                </span>
            @elseif($license->status == 'suspended')
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                    <i data-lucide="x-circle" class="w-4 h-4 mr-1.5 mt-0.5"></i> Suspended
                </span>
            @elseif($license->status == 'expired')
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                    <i data-lucide="alert-triangle" class="w-4 h-4 mr-1.5 mt-0.5"></i> Expired
                </span>
            @else
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200 dark:bg-surface-700 dark:text-surface-300">
                    <i data-lucide="hourglass-split" class="w-4 h-4 mr-1.5 mt-0.5"></i> {{ ucfirst($license->status) }}
                </span>
            @endif
        </div>
    </div>
    
    <div class="px-6 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700 dark:sm:divide-surface-700">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-white dark:bg-surface-800">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Product Name</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-semibold">
                    {{ $license->product->name ?? 'Unknown Product' }}
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900/30">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">License Key</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    <div class="flex items-center">
                        <code class="bg-surface-100 dark:bg-surface-900 px-3 py-1 rounded border border-surface-300 dark:border-surface-600 font-mono text-primary-600 dark:text-primary-400 mr-3">
                            {{ $license->license_key }}
                        </code>
                        <button onclick="copyToClipboard('{{ $license->license_key }}')" class="text-surface-400 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none" title="Copy to clipboard">
                            <i data-lucide="clipboard" class="w-4 h-4"></i>
                        </button>
                    </div>
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-white dark:bg-surface-800">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Current Domain</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    @if($license->domain)
                        <span class="text-surface-900 dark:text-white">{{ $license->domain }}</span>
                    @else
                        <span class="text-surface-500 dark:text-surface-400 italic">Not set (Unrestricted or pending activation)</span>
                    @endif
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900/30">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Activated At</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ $license->activated_at ? \Carbon\Carbon::parse($license->activated_at)->format('F d, Y - H:i') : 'Not activated yet' }}
                </dd>
            </div>
            
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-white dark:bg-surface-800">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                    {{ \Carbon\Carbon::parse($license->created_at)->format('F d, Y - H:i') }}
                </dd>
            </div>
        </dl>
    </div>
    
    <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
        @if($license->status == 'active')
            <a href="{{ route('customer.licenses.credentials', $license->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i data-lucide="shield-lock" class="w-4 h-4 mr-2"></i> View API Credentials
            </a>
        @elseif($license->status == 'pending' || $license->status == 'inactive')
            <form class="form-confirm-submit" action="{{ route('customer.licenses.activate', $license->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i data-lucide="play-circle" class="w-4 h-4 mr-2"></i> Activate License
                </button>
            </form>
        @endif
        <a href="{{ route('customer.subscriptions.create', ['license_id' => $license->id]) }}" class="inline-flex justify-center py-2 px-4 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i data-lucide="repeat" class="w-4 h-4"></i> Extend via Subscription
        </a>
    </div>
        </div>
    </div>
</div>
@endsection
