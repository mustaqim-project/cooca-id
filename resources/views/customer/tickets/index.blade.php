@extends('layouts.customer')
@section('title', 'Support Tickets')
@section('breadcrumb')
    <span class="crumb-current">Support Tickets</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-headset" style="color:var(--primary);margin-right:10px;"></i>Support Tickets</h1>
        <p class="page-subtitle">Get help from our support team. We typically respond within 2 hours.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Ticket
        </a>
    </div>
</div>

{{-- Status chips --}}
@php
    $open   = $tickets->whereIn('status', ['open'])->count();
    $inProg = $tickets->where('status', 'in_progress')->count();
    $resolved = $tickets->where('status', 'resolved')->count();
    $closed   = $tickets->where('status', 'closed')->count();
@endphp
<div class="flex gap-3 mb-5">
    <a href="{{ route('customer.tickets.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline' }} btn-sm">
        All <span class="badge badge-primary" style="margin-left:4px;">{{ $tickets->count() }}</span>
    </a>
    <a href="{{ route('customer.tickets.index', ['status' => 'open']) }}" class="btn {{ request('status') === 'open' ? 'btn-primary' : 'btn-outline' }} btn-sm">
        Open <span class="badge badge-danger" style="margin-left:4px;">{{ $open }}</span>
    </a>
    <a href="{{ route('customer.tickets.index', ['status' => 'in_progress']) }}" class="btn {{ request('status') === 'in_progress' ? 'btn-primary' : 'btn-outline' }} btn-sm">
        In Progress <span class="badge badge-warning" style="margin-left:4px;">{{ $inProg }}</span>
    </a>
    <a href="{{ route('customer.tickets.index', ['status' => 'resolved']) }}" class="btn {{ request('status') === 'resolved' ? 'btn-primary' : 'btn-outline' }} btn-sm">
        Resolved
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="font-bold text-sm text-muted">{{ $ticket->ticket_number ?? '#' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="font-semibold">{{ $ticket->subject }}</div>
                            @if($ticket->department)
                                <div class="text-xs text-muted">{{ $ticket->department }}</div>
                            @endif
                        </td>
                        <td>
                            @if($ticket->priority === 'high')    <span class="badge badge-danger">High</span>
                            @elseif($ticket->priority === 'medium') <span class="badge badge-warning">Medium</span>
                            @else <span class="badge badge-muted">Low</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status === 'open')        <span class="badge badge-primary">Open</span>
                            @elseif($ticket->status === 'in_progress') <span class="badge badge-warning">In Progress</span>
                            @elseif($ticket->status === 'resolved')    <span class="badge badge-success">Resolved</span>
                            @elseif($ticket->status === 'closed')      <span class="badge badge-muted">Closed</span>
                            @endif
                        </td>
                        <td class="text-xs text-muted">{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('customer.tickets.show', $ticket->id) }}" class="btn btn-ghost btn-sm">
                                View <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">🎧</div>
                                <div class="empty-state-title">No Tickets Found</div>
                                <div class="empty-state-text">Having an issue? Create a support ticket and our team will help.</div>
                                <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary">Create Ticket</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($tickets, 'hasPages') && $tickets->hasPages())
        <div class="card-footer">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
