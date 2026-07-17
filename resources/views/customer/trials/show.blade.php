@extends('layouts.customer')

@section('title', 'Trial Details')
@section('subtitle', 'View trial status and information')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between">
            <a href="{{ route('customer.trials.index') }}"
                class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Trials
            </a>
        </div>

        <div class="corporate-card overflow-hidden">
            <div
                class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Request Information</h3>
                @php
                    $statusColors = [
                        'submitted' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'reviewing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                        'provisioning' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                        'trial_active' =>
                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'trial_expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                    ];
                    $colorClass = $statusColors[$trial->status] ?? 'bg-surface-100 text-surface-800';
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $colorClass }}">
                    {{ str_replace('_', ' ', ucfirst($trial->status)) }}
                </span>
            </div>

            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Product</dt>
                        <dd class="mt-1 text-sm font-semibold text-surface-900 dark:text-white">
                            {{ $trial->product->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Requested Subdomain</dt>
                        <dd class="mt-1 text-sm font-semibold text-surface-900 dark:text-white">
                            {{ $trial->requested_subdomain }}.cooca.id</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Request Date</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white">
                            {{ $trial->created_at->format('F d, Y - H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white">
                            {{ $trial->updated_at->format('F d, Y - H:i') }}</dd>
                    </div>
                    @if ($trial->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Notes</dt>
                            <dd
                                class="mt-1 text-sm text-surface-900 dark:text-white bg-surface-50 dark:bg-surface-800 p-3 rounded-lg">
                                {{ $trial->notes }}</dd>
                        </div>
                    @endif
                    @if ($trial->rejection_reason)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-red-500 dark:text-red-400">Rejection Reason</dt>
                            <dd
                                class="mt-1 text-sm text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20 p-3 rounded-lg">
                                {{ $trial->rejection_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
