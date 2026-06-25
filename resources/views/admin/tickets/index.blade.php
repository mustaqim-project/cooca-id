@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-ticket-alt me-2"></i>Support Tickets
            </h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Tickets</p>
                            <h4 class="mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-ticket-alt fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Open</p>
                            <h4 class="mb-0 text-danger">{{ $stats['open'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-envelope-open fa-lg text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">In Progress</p>
                            <h4 class="mb-0 text-warning">{{ $stats['in_progress'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-spinner fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Resolved</p>
                            <h4 class="mb-0 text-success">{{ $stats['resolved'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tickets.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search subject/customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="priority" class="form-select">
                            <option value="">All Priority</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">All Tickets ({{ $tickets->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
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
                            <td><strong>#{{ $ticket->id }}</strong></td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;">
                                    <strong>{{ $ticket->subject }}</strong>
                                </div>
                            </td>
                            <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                            <td>
                                @if($ticket->priority === 'urgent')
                                    <span class="badge bg-danger">Urgent</span>
                                @elseif($ticket->priority === 'high')
                                    <span class="badge bg-warning text-dark">High</span>
                                @elseif($ticket->priority === 'medium')
                                    <span class="badge bg-info">Medium</span>
                                @else
                                    <span class="badge bg-secondary">Low</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->status === 'open')
                                    <span class="badge bg-danger">Open</span>
                                @elseif($ticket->status === 'in_progress')
                                    <span class="badge bg-warning text-dark">In Progress</span>
                                @elseif($ticket->status === 'resolved')
                                    <span class="badge bg-success">Resolved</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->assignedTo)
                                    <span class="badge bg-primary">{{ $ticket->assignedTo->name }}</span>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-outline-success" onclick="replyTicket({{ $ticket->id }})" title="Reply">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No tickets found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} entries</p>
                <div class="pagination-wrapper">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function replyTicket(id) {
        Swal.fire({
            title: 'Reply to Ticket',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Your Reply</label>
                        <textarea class="form-control" id="replyMessage" rows="4" placeholder="Type your reply here..."></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Send Reply',
            cancelButtonText: 'Cancel',
            width: '600px'
        });
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
