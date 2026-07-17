@extends('layouts.admin')

@section('title', 'Support Tickets')
@section('subtitle', 'Manage customer support requests')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div class="input-group shadow-sm" style="max-width: 320px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                placeholder="Search subject, customer...">
        </div>
    </div>

    <div class="card card-saas border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ticketsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="ps-4 text-muted font-monospace">#{{ $ticket->id }}</td>
                                <td>
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                        class="fw-semibold text-dark text-decoration-none d-block text-truncate"
                                        style="max-width:260px;" title="{{ $ticket->subject }}">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $ticket->customer->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @php
                                        $priorityMap = [
                                            'urgent' => 'danger',
                                            'high' => 'warning',
                                            'medium' => 'info',
                                            'low' => 'secondary',
                                        ];
                                        $pBadge = $priorityMap[$ticket->priority] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $pBadge }} bg-opacity-10 text-{{ $pBadge }} px-3 py-2 rounded-pill fw-medium">{{ ucfirst($ticket->priority) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'open' => 'danger',
                                            'in_progress' => 'warning',
                                            'resolved' => 'success',
                                            'closed' => 'secondary',
                                        ];
                                        $sBadge = $statusMap[$ticket->status] ?? 'secondary';
                                        $statusLabel = match ($ticket->status) {
                                            'in_progress' => 'In Progress',
                                            default => ucfirst($ticket->status),
                                        };
                                    @endphp
                                    <span
                                        class="badge bg-{{ $sBadge }} bg-opacity-10 text-{{ $sBadge }} px-3 py-2 rounded-pill fw-medium">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    @if ($ticket->assignedTo)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 24px; height: 24px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-medium">{{ $ticket->assignedTo->name }}</span>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted border fst-italic">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i
                                            class="bi bi-calendar3 me-1 opacity-50"></i>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                        class="btn btn-light btn-sm rounded-pill px-3 fw-medium">
                                        <i class="bi bi-eye me-1 text-primary"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-50 d-block mb-3"></i>
                                        <h6 class="fw-semibold text-dark">No tickets found</h6>
                                        <p class="text-muted fs-sm mb-0">All customer support requests will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($tickets) && method_exists($tickets, 'links') && $tickets->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $tickets->links() }}
            </div>
        @endif
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
