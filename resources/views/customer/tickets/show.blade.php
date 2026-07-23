@extends('customer.layouts.app')

@section('title', $ticket->subject)

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('customer.tickets.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <div>
                        <h2 class="mb-1 fw-bold text-truncate" style="max-width: 400px;">{{ $ticket->subject }}</h2>
                        <p class="text-secondary mb-0">Ticket #{{ $ticket->id }}</p>
                    </div>
                </div>
                
                <div>
                    @php
                        $statusClass = match($ticket->status) {
                            'open' => 'warning',
                            'in_progress' => 'info',
                            'resolved' => 'success',
                            'closed' => 'secondary',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-4 py-2 fs-6">
                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                    </span>
                </div>
            </div>

            <!-- Ticket Message -->
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden mb-5">
                <div class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-semibold">{{ $ticket->subject }}</h5>
                        <p class="text-secondary mb-0 fs-7">Submitted on {{ $ticket->created_at->format('F d, Y - H:i') }}</p>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5 text-secondary" style="white-space: pre-wrap; line-height: 1.6;">{{ $ticket->message }}</div>
            </div>

            <!-- Replies -->
            <h5 class="fw-bold mb-4">Conversation</h5>
            <div class="d-flex flex-column gap-4 mb-5">
                @forelse($ticket->replies as $reply)
                    <div class="card border-0 shadow-sm rounded-4 {{ $reply->user_type === 'admin' ? 'bg-primary-subtle border-start border-primary border-4' : 'glass' }}">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold {{ $reply->user_type === 'admin' ? 'text-primary' : 'text-dark' }}">
                                    {{ $reply->user->name ?? ($reply->user_type === 'admin' ? 'Support Agent' : 'You') }}
                                    @if ($reply->user_type === 'admin')
                                        <span class="badge bg-primary rounded-pill ms-2">Support</span>
                                    @endif
                                </div>
                                <div class="text-secondary fs-7">{{ $reply->created_at->format('M d, Y H:i') }}</div>
                            </div>
                            <div class="text-secondary" style="white-space: pre-wrap; line-height: 1.6;">{{ $reply->message }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-secondary py-4 fst-italic">No replies yet.</div>
                @endforelse
            </div>

            <!-- Reply Form -->
            @if (in_array($ticket->status, ['open', 'in_progress']))
                <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="mb-0 fw-semibold"><i class="bi bi-reply me-2"></i> Add Reply</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('customer.tickets.reply', $ticket) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea name="message" rows="4" class="form-control bg-light border-light py-3 text-secondary" placeholder="Type your reply here..." required></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 hover-lift fw-medium">
                                    Send Reply <i class="bi bi-send ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden text-center p-4">
                    <div class="text-secondary">
                        <i class="bi bi-lock me-2"></i> This ticket is closed. If you have further questions, please create a new ticket.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
