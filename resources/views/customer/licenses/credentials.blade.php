@extends('layouts.customer')

@section('title', 'API Credentials')
@section('subtitle', 'Your secret tokens for accessing the product API')

@section('content')
<div class="mb-4">
    <a href="{{ route('customer.licenses.show', $license->id) }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to License Details
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-8">
        <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900/50">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 dark:bg-red-900/30 rounded-md p-3">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-surface-900 dark:text-white">Keep Your Secrets Safe</h3>
                    <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">Do not share your API tokens with anyone or commit them to public repositories.</p>
                </div>
            </div>
        </div>
        
        <div class="p-6 md:p-8 space-y-8">
            <!-- License Key -->
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">License Key</label>
                <div class="flex">
                    <div class="relative flex-grow">
                        <input type="text" id="license_key" value="{{ $license->license_key }}" readonly class="block w-full bg-surface-50 dark:bg-surface-900 border border-surface-300 dark:border-surface-600 rounded-md rounded-r-none py-3 px-4 text-sm font-mono text-surface-900 dark:text-surface-300 focus:outline-none">
                    </div>
                    <button type="button" onclick="copyToClipboard('license_key')" class="relative -ml-px inline-flex items-center space-x-2 px-4 py-3 border border-surface-300 dark:border-surface-600 text-sm font-medium rounded-r-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none">
                        <i data-lucide="clipboard" class="w-4 h-4"></i>
                        <span>Copy</span>
                    </button>
                </div>
                <p class="mt-2 text-xs text-surface-500 dark:text-surface-400">Used for identifying your purchase when interacting with support or API.</p>
            </div>
            
            <!-- API Token (if any) -->
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Access Token / API Key</label>
                <div class="flex" x-data="{ showToken: false }">
                    <div class="relative flex-grow">
                        <!-- We might only have the token encrypted or generated on the fly, assuming $license->token exists for demo -->
                        <input :type="showToken ? 'text' : 'password'" id="api_token" value="{{ $license->token ?? 'demo_token_12345_abcdef_xxxxxxxxxx' }}" readonly class="block w-full bg-surface-50 dark:bg-surface-900 border border-surface-300 dark:border-surface-600 rounded-l-md py-3 px-4 text-sm font-mono text-surface-900 dark:text-surface-300 focus:outline-none">
                    </div>
                    <button type="button" @click="showToken = !showToken" class="relative -ml-px inline-flex items-center px-4 py-3 border border-surface-300 dark:border-surface-600 text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none">
                        <i class="bi" :class="showToken ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                    <button type="button" onclick="copyToClipboard('api_token')" class="relative -ml-px inline-flex items-center space-x-2 px-4 py-3 border border-surface-300 dark:border-surface-600 text-sm font-medium rounded-r-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none">
                        <i data-lucide="clipboard" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="mt-2 text-xs text-surface-500 dark:text-surface-400">Include this token in the <code class="text-primary-600 dark:text-primary-400">Authorization: Bearer</code> header for API requests.</p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700 flex justify-end">
            <!-- Regenerate Token might be a feature in the future -->
            <button type="button" onclick="alert('Feature to regenerate token is coming soon!')" class="inline-flex justify-center py-2 px-4 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none">
                <i data-lucide="arrow-clockwise" class="w-4 h-4 mr-2"></i> Regenerate Token
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        
        // Save current type, change to text to copy if it's password
        const originalType = copyText.type;
        if (originalType === 'password') {
            copyText.type = 'text';
        }
        
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        
        navigator.clipboard.writeText(copyText.value).then(() => {
            // Restore original type
            if (originalType === 'password') {
                copyText.type = 'password';
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Copied to clipboard.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    }
</script>
@endpush