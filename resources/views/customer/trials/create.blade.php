@extends('layouts.customer')

@section('title', 'Request Trial')
@section('subtitle', 'Try our products before you buy')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between">
            <a href="{{ route('customer.trials.index') }}"
                class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Trials
            </a>
        </div>

        <div class="corporate-card">
            <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Trial Request Form</h3>
            </div>
            <form action="{{ route('customer.trials.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Select Product
                        *</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        @foreach ($products as $product)
                            <label
                                class="relative flex cursor-pointer rounded-lg border bg-white dark:bg-surface-800 p-4 shadow-sm focus:outline-none">
                                <input type="radio" name="product_id" value="{{ $product->id }}" class="sr-only peer"
                                    required>
                                <div class="peer-checked:border-primary-500 peer-checked:ring-1 peer-checked:ring-primary-500 absolute -inset-px rounded-lg border-2 border-transparent pointer-events-none"
                                    aria-hidden="true"></div>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span
                                            class="block text-sm font-medium text-surface-900 dark:text-white">{{ $product->name }}</span>
                                        <span
                                            class="mt-1 flex items-center text-sm text-surface-500 dark:text-surface-400">{{ Str::limit($product->description, 50) }}</span>
                                    </span>
                                </span>
                                <i data-lucide="check-circle-2"
                                    class="h-5 w-5 text-primary-600 hidden peer-checked:block"></i>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Requested Subdomain
                        *</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="requested_subdomain" required pattern="[a-z0-9-]+"
                            title="Only lowercase letters, numbers, and dashes are allowed"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border border-r-0 border-surface-300 dark:border-surface-600 dark:bg-surface-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <span
                            class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 text-surface-500 dark:text-surface-400 sm:text-sm">
                            .cooca.id
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-surface-500">Only lowercase letters, numbers, and hyphens.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Additional
                        Notes</label>
                    <textarea name="notes" rows="4"
                        class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                        placeholder="Any specific requirements or questions?"></textarea>
                </div>

                <div class="pt-4 border-t border-surface-200 dark:border-surface-700 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
