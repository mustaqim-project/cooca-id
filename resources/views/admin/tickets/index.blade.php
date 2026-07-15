@extends('layouts.admin')

@section('title', 'Support Tickets')
@section('subtitle', 'Manage customer support requests')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:320px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search subject, customer...">
            </div>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="ticketsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="text-muted small">#{{ $ticket->id }}</td>
                                <td>
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                        class="fw-semibold text-decoration-none"
                                        style="max-width:260px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $priorityMap = [
                                            'urgent' => 'danger',
                                            'high' => 'warning',
                                            'medium' => 'info',
                                            'low' => 'neutral',
                                        ];
                                        $pBadge = $priorityMap[$ticket->priority] ?? 'neutral';
                                    @endphp
                                    <span
                                        class="badge-saas badge-saas-{{ $pBadge }}">{{ ucfirst($ticket->priority) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'open' => 'danger',
                                            'in_progress' => 'warning',
                                            'resolved' => 'success',
                                            'closed' => 'neutral',
                                        ];
                                        $sBadge = $statusMap[$ticket->status] ?? 'neutral';
                                        $statusLabel = match ($ticket->status) {
                                            'in_progress' => 'In Progress',
                                            default => ucfirst($ticket->status),
                                        };
                                    @endphp
                                    <span class="badge-saas badge-saas-{{ $sBadge }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    @if ($ticket->assignedTo)
                                        <span class="badge-saas badge-saas-primary">{{ $ticket->assignedTo->name }}</span>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-icon" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                        <div class="empty-state-title">No tickets found</div>
                                        <div class="empty-state-description">All customer support requests will appear here.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#ticketsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
