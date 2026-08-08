@extends('layouts.customer')
@section('title', 'Ticket #' . $ticket->ticket_number)
@section('breadcrumb')
    <a href="{{ route('customer.tickets.index') }}" class="crumb-link">Tickets</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">{{ $ticket->subject }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-headset" style="color:var(--primary);margin-right:10px;"></i>{{ $ticket->subject }}</h1>
        <p class="page-subtitle">Ticket #{{ $ticket->ticket_number ?? $ticket->id }} · Created {{ $ticket->created_at->format('d M Y H:i') }}</p>
    </div>
    <a href="{{ route('customer.tickets.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid-31">
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- Original Ticket Message --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="user-avatar" style="width:34px;height:34px;font-size:12px;">
                        {{ strtoupper(substr(auth('customer')->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm">{{ auth('customer')->user()->name }} (You)</div>
                        <div class="text-xs text-muted">{{ $ticket->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-sm" style="line-height:1.7;white-space:pre-wrap;">{{ $ticket->message }}</div>
            </div>
        </div>

        {{-- Ticket Replies Timeline --}}
        @foreach($ticket->replies as $reply)
        <div class="card" style="{{ $reply->user_type === 'admin' ? 'border-left:4px solid var(--primary);' : '' }}">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="user-avatar" style="width:34px;height:34px;font-size:12px;{{ $reply->user_type === 'admin' ? 'background:linear-gradient(135deg,var(--primary),var(--accent));' : '' }}">
                        {{ $reply->user_type === 'admin' ? 'CS' : strtoupper(substr(auth('customer')->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm">
                            {{ $reply->user_type === 'admin' ? 'COOCA Support Team' : auth('customer')->user()->name }}
                            @if($reply->user_type === 'admin')
                                <span class="badge badge-primary" style="margin-left:6px;">Support Agent</span>
                            @endif
                        </div>
                        <div class="text-xs text-muted">{{ $reply->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-sm" style="line-height:1.7;white-space:pre-wrap;">{{ $reply->message }}</div>
            </div>
        </div>
        @endforeach

        {{-- Reply Form --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Add Reply</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.tickets.show', $ticket->id) }}/reply">
                    @csrf
                    <div class="form-group">
                        <textarea name="message" class="form-textarea" rows="4" required placeholder="Type your reply here…"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Send Reply
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Ticket Sidebar Meta --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header"><div class="card-title">Ticket Information</div></div>
            <div class="card-body">
                <div class="stats-row">
                    <span class="text-sm text-muted">Status</span>
                    <span class="badge badge-primary">{{ ucfirst($ticket->status) }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Priority</span>
                    <span class="badge badge-warning">{{ ucfirst($ticket->priority) }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Department</span>
                    <span class="text-sm font-semibold">{{ ucfirst($ticket->department ?? 'Support') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
