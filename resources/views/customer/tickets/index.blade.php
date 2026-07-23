@extends('customer.layouts.app')

@section('title', 'My Tickets')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Support Tickets</h2>
                <p class="text-secondary mb-0">Get help and support for your products.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-plus-lg me-1"></i> Create Ticket
                </a>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Subject</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Priority</th>
                            <th class="py-3 px-3 border-0">Created At</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-semibold">{{ $ticket->subject }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusClass = match($ticket->status) {
                                            'open' => 'warning',
                                            'in_progress' => 'info',
                                            'resolved' => 'success',
                                            'closed' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $priorityClass = match($ticket->priority) {
                                            'low' => 'text-secondary',
                                            'medium' => 'text-warning',
                                            'high' => 'text-danger fw-bold',
                                            default => 'text-secondary'
                                        };
                                    @endphp
                                    <span class="fs-7 {{ $priorityClass }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $ticket->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('customer.tickets.show', $ticket) }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                                        View <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Tickets Found</h6>
                                    <p class="fs-7">You have no support tickets.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($tickets, 'hasPages') && $tickets->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
