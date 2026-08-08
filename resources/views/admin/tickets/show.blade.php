@extends('layouts.admin')

@section('title', 'Ticket Reply — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.tickets.index') }}">Tickets</a>
            <span>/</span>
            <span>Reply</span>
        </div>
        <h1 class="page-title">{{ $ticket->subject ?? 'Ticket #TCK' }}</h1>
    </div>
</div>

<div class="grid-31">
    <div class="card">
        <div class="card-header"><div class="card-title">Conversation Thread</div></div>
        <div class="card-body">
            <div class="p-4 rounded mb-4" style="background: var(--bg-secondary);">
                <div class="font-bold text-sm mb-1">{{ $ticket->customer->name ?? 'Client' }}</div>
                <div class="text-sm">{{ $ticket->description ?? 'Need assistance with server installation.' }}</div>
            </div>

            <form action="{{ route('admin.tickets.reply', $ticket->id ?? 1) }}" method="POST" class="mt-6">
                @csrf
                <div class="form-group">
                    <label class="form-label">Admin Response Reply *</label>
                    <textarea name="message" class="form-textarea" rows="5" required placeholder="Type official admin response..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">🎧 Post Reply</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Ticket Status</div></div>
        <div class="card-body">
            <div class="form-group">
                <div class="text-xs text-muted font-bold uppercase">Status</div>
                <span class="badge badge-warning">{{ strtoupper($ticket->status ?? 'OPEN') }}</span>
            </div>
            <form action="{{ route('admin.tickets.resolve', $ticket->id ?? 1) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-success w-full">Mark Resolved</button>
            </form>
        </div>
    </div>
</div>
@endsection
