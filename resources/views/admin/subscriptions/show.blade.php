@extends('layouts.admin')

@section('title', 'Subscription Details')
@section('subtitle', 'View complete details for subscription')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Subscriptions
    </a>
    
    @if(in_array($subscription->status, ['active', 'trial']))
    <div class="flex gap-2">
        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none" onclick="cancelSubscription()">
            <i data-lucide="x" class="w-4 h-4"></i> Cancel Subscription
        </button>
    </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white">
                    Subscription #{{ substr($subscription->id, 0, 8) }}
                </h3>
                <div>
                    @php
                        $statusClass = match($subscription->status) {
                            'active' => 'bg-green-100 text-green-800',
                            'trial' => 'bg-blue-100 text-blue-800',
                            'expired' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-surface-100 text-surface-800',
                            default => 'bg-surface-100 text-surface-800'
                        };
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $statusClass }}">
                        {{ strtoupper($subscription->status) }}
                    </span>
                </div>
            </div>
            
            <div class="px-6 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Full Subscription ID</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                            {{ $subscription->id }}
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Customer</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            <a href="{{ route('admin.customers.show', $subscription->customer_id ?? 0) }}" class="text-primary-600 hover:underline font-medium">
                                {{ $subscription->customer->name ?? 'Unknown Customer' }}
                            </a>
                            <div class="text-surface-500 dark:text-surface-400 text-xs mt-1">{{ $subscription->customer->email ?? '' }}</div>
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Product</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            <a href="{{ route('admin.products.show', $subscription->product_id ?? 0) }}" class="text-primary-600 hover:underline font-medium">
                                {{ $subscription->product->name ?? 'Unknown Product' }}
                            </a>
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Associated License</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            @if($subscription->license)
                                <a href="{{ route('admin.licenses.show', $subscription->license->id) }}" class="text-primary-600 hover:underline">
                                    {{ $subscription->license->domain ?? 'Unconfigured License' }}
                                </a>
                                <span class="px-2 py-0.5 ml-2 text-xs rounded-full {{ $subscription->license->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200' }}">
                                    {{ ucfirst($subscription->license->status) }}
                                </span>
                            @else
                                <span class="text-surface-500 dark:text-surface-400 italic">No license generated yet</span>
                            @endif
                        </dd>
                    </div>
                    
                    @if($subscription->status == 'cancelled' && $subscription->cancellation_reason)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-red-50">
                        <dt class="text-sm font-medium text-red-800">Cancellation Reason</dt>
                        <dd class="mt-1 text-sm text-red-900 sm:mt-0 sm:col-span-2">
                            {{ $subscription->cancellation_reason }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Subscription Timeline</h3>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($timeline as $index => $event)
                        <li>
                            <div class="relative pb-8">
                                @if($index !== count($timeline) - 1)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-surface-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-primary-500">
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-surface-500 dark:text-surface-400">
                                                <span class="font-medium text-surface-900 dark:text-white">{{ $event['event'] }}</span>
                                                <br>
                                                {{ $event['description'] }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-surface-500 dark:text-surface-400">
                                            <time datetime="{{ $event['date'] }}">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }}</time>
                                            <br>
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($event['date'])->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Right Column: Summary & Actions -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Important Dates -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Important Dates</h3>
            </div>
            <ul class="divide-y divide-surface-200 dark:divide-surface-700">
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-surface-500 dark:text-surface-400">Created At</span>
                        <span class="text-sm text-surface-900 dark:text-white">{{ \Carbon\Carbon::parse($subscription->created_at)->format('M d, Y') }}</span>
                    </div>
                </li>
                @if($subscription->activated_at)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-surface-500 dark:text-surface-400">Activated At</span>
                        <span class="text-sm text-surface-900 dark:text-white">{{ \Carbon\Carbon::parse($subscription->activated_at)->format('M d, Y') }}</span>
                    </div>
                </li>
                @endif
                <li class="px-6 py-4 bg-primary-50">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-primary-800">Expires At</span>
                        <span class="text-sm font-bold text-primary-900">
                            {{ $subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('M d, Y') : 'Lifetime' }}
                        </span>
                    </div>
                </li>
                @if($subscription->cancelled_at)
                <li class="px-6 py-4 bg-red-50">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-red-800">Cancelled At</span>
                        <span class="text-sm font-bold text-red-900">{{ \Carbon\Carbon::parse($subscription->cancelled_at)->format('M d, Y') }}</span>
                    </div>
                </li>
                @endif
            </ul>
        </div>
        
        <!-- Transactions -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Related Transactions</h3>
            </div>
            
            @if($subscription->transactions && $subscription->transactions->count() > 0)
            <ul class="divide-y divide-surface-200 dark:divide-surface-700">
                @foreach($subscription->transactions as $transaction)
                <li class="p-4 hover:bg-surface-50 dark:bg-surface-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-surface-900 dark:text-white">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-surface-500 dark:text-surface-400">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->status == 'paid' || 'settlement' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="block text-xs text-primary-600 hover:underline mt-1">View</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="p-6 text-center text-sm text-surface-500 dark:text-surface-400">
                No transactions linked directly to this subscription.
            </div>
            @endif
        </div>
        
    </div>
</div>

<!-- Cancel Form -->
<form class="form-confirm-submit" id="cancel-form" action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="reason" id="cancel-reason">
    <input type="hidden" name="immediate" id="cancel-immediate" value="1">
</form>

@endsection

@push('scripts')
<script>
    function cancelSubscription() {
        Swal.fire({
            title: 'Cancel Subscription',
            text: "Are you sure you want to cancel this subscription?",
            icon: 'warning',
            input: 'textarea',
            inputLabel: 'Reason for cancellation (sent to customer)',
            inputPlaceholder: 'Customer requested cancellation, violation of terms, etc.',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write something!'
                }
            },
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel it!',
            html: `
                <div class="mt-4 text-left">
                    <label class="flex items-center">
                        <input type="checkbox" id="swal-immediate" class="rounded border-surface-300 dark:border-surface-600 text-red-600 focus:ring-red-500" checked>
                        <span class="ml-2 text-sm text-surface-600 dark:text-surface-400">Revoke associated license immediately</span>
                    </label>
                </div>
            `,
            preConfirm: () => {
                return [
                    document.getElementById('swal2-input').value,
                    document.getElementById('swal-immediate').checked ? '1' : '0'
                ]
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancel-reason').value = result.value[0];
                document.getElementById('cancel-immediate').value = result.value[1];
                document.getElementById('cancel-form').submit();
            }
        })
    }
</script>
@endpush