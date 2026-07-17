@extends('layouts.customer')

@section('title', $ticket->subject)
@section('subtitle', 'Ticket #' . $ticket->id)

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between">
            <a href="{{ route('customer.tickets.index') }}"
                class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Tickets
            </a>
            @php
                $statusColors = [
                    'open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                    'resolved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                ];
                $colorClass = $statusColors[$ticket->status] ?? 'bg-surface-100 text-surface-800';
            @endphp
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $colorClass }}">
                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
            </span>
        </div>

        <!-- Ticket Message -->
        <div class="corporate-card overflow-hidden">
            <div
                class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-surface-900 dark:text-white">{{ $ticket->subject }}</h3>
                    <p class="text-sm text-surface-500 mt-1">Submitted on {{ $ticket->created_at->format('F d, Y - H:i') }}
                    </p>
                </div>
            </div>
            <div class="p-6 text-surface-700 dark:text-surface-300 whitespace-pre-wrap leading-relaxed">
                {{ $ticket->message }}</div>
        </div>

        <!-- Replies -->
        <div class="space-y-4">
            <h4 class="text-lg font-medium text-surface-900 dark:text-white">Conversation</h4>
            @forelse($ticket->replies as $reply)
                <div
                    class="corporate-card p-6 {{ $reply->user_type === 'admin' ? 'border-l-4 border-l-primary-500 bg-primary-50/10 dark:bg-primary-900/10' : '' }}">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-semibold text-sm text-surface-900 dark:text-white">
                            {{ $reply->user->name ?? ($reply->user_type === 'admin' ? 'Support Agent' : 'You') }}
                            @if ($reply->user_type === 'admin')
                                <span
                                    class="ml-2 px-2 py-0.5 text-xs bg-primary-100 text-primary-800 dark:bg-primary-900/50 dark:text-primary-300 rounded">Support</span>
                            @endif
                        </span>
                        <span class="text-xs text-surface-500">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="text-sm text-surface-700 dark:text-surface-300 whitespace-pre-wrap">{{ $reply->message }}
                    </div>
                </div>
            @empty
                <p class="text-sm text-surface-500 italic">No replies yet.</p>
            @endforelse
        </div>

        <!-- Reply Form -->
        @if (in_array($ticket->status, ['open', 'in_progress']))
            <div class="corporate-card">
                <div
                    class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                    <h3 class="text-md font-medium text-surface-900 dark:text-white">Add Reply</h3>
                </div>
                <form action="{{ route('customer.tickets.reply', $ticket) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <textarea name="message" rows="4" required
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white"
                            placeholder="Type your reply here..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div
                class="corporate-card p-6 text-center text-surface-500 dark:text-surface-400 bg-surface-50 dark:bg-surface-900/50">
                This ticket is closed. If you have further questions, please create a new ticket.
            </div>
        @endif
    </div>
@endsection
