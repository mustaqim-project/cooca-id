@extends('layouts.admin')

@section('title', 'Support Tickets — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Support Tickets</span>
        </div>
        <h1 class="page-title">Customer Support Helpdesk</h1>
        <p class="page-subtitle">Respond to customer technical inquiries, bug reports, and server setup requests.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket Code & Subject</th>
                        <th>Customer</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets ?? [] as $tkt)
                        @php $tObj = is_array($tkt) ? (object)$tkt : $tkt; @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-base">{{ $tObj->subject ?? 'Issue' }}</div>
                                <code class="text-xs text-primary">#TCK-{{ $tObj->id ?? 1 }}</code>
                            </td>
                            <td>{{ $tObj->customer->name ?? 'Client' }}</td>
                            <td>
                                <span class="badge badge-danger">{{ strtoupper($tObj->priority ?? 'HIGH') }}</span>
                            </td>
                            <td>
                                @if(($tObj->status ?? '') === 'resolved')
                                    <span class="badge badge-success">RESOLVED</span>
                                @else
                                    <span class="badge badge-warning">OPEN</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.tickets.show', $tObj->id ?? 1) }}" class="btn btn-ghost btn-sm">🎧 Reply</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No support tickets open.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
