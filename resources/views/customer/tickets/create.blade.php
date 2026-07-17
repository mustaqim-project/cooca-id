@extends('layouts.customer')

@section('title', 'Create Ticket')
@section('subtitle', 'Submit a new support request')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between">
            <a href="{{ route('customer.tickets.index') }}"
                class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Tickets
            </a>
        </div>

        <div class="corporate-card">
            <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Ticket Details</h3>
            </div>
            <form action="{{ route('customer.tickets.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                        class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white"
                        placeholder="Brief description of the issue">
                </div>

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Priority</label>
                    <select name="priority"
                        class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                        <option value="low" @selected(old('priority') === 'low')>Low</option>
                        <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                        <option value="high" @selected(old('priority') === 'high')>High</option>
                    </select>
                    <p class="mt-1 text-sm text-surface-500">Select high priority only for critical system issues.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Message *</label>
                    <textarea name="message" rows="6" required
                        class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white"
                        placeholder="Provide detailed information about your issue...">{{ old('message') }}</textarea>
                </div>

                <div class="pt-4 border-t border-surface-200 dark:border-surface-700 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
