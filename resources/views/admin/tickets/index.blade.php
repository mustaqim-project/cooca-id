@extends('admin.layouts.app')

@section('title', 'Support Tickets')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Support Tickets</h2>
                <p class="text-secondary mb-0">Monitor customer inquiries, technical issues, and ticket SLA resolutions.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary">
                    <i class="bi bi-filter me-2"></i> Filter Status
                </button>
            </div>
        </div>

        <!-- Ticket Summary Bento -->
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Open Tickets</div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1"><i
                                class="bi bi-exclamation-circle me-1"></i> Action Needed</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">14</h3>
                        <span class="text-secondary fs-8">unassigned</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">In Progress</div>
                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1"><i
                                class="bi bi-clock me-1"></i> Active</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">28</h3>
                        <span class="text-secondary fs-8">handling now</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Avg. Response Time</div>
                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1"><i
                                class="bi bi-lightning me-1"></i> Fast</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">18m</h3>
                        <span class="text-success fs-8"><i class="bi bi-arrow-down me-1"></i>-4m vs last week</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Resolved (This Month)</div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i
                                class="bi bi-check-circle me-1"></i> Done</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">342</h3>
                        <span class="text-secondary fs-8">98.4% SLA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search ticket # or subject...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Ticket # / Subject</th>
                            <th class="py-3 px-3 border-0">Customer</th>
                            <th class="py-3 px-3 border-0">Priority</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Last Updated</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($tickets ?? [
                                (object)
    ['id' => 1042, 'ticket_number' => 'TCK-2026-1042', 'subject' => 'Cannot activate POS license after renewal', 'customer_name' => 'PT Sumber Makmur', 'customer_email' => 'tech@sumbermakmur.com', 'priority' => 'High', 'status' => 'Open', 'updated_at' => now()->subMinutes(12)],
                                (object)['id' => 1041, 'ticket_number' => 'TCK-2026-1041', 'subject' => 'Midtrans callback webhook returning 500 error', 'customer_name' => 'Budi Santoso', 'customer_email' => 'budi.s@gmail.com', 'priority' => 'Urgent', 'status' => 'In Progress', 'updated_at' => now()->subHours(2)],
                                (object)['id' => 1040, 'ticket_number' => 'TCK-2026-1040', 'subject' => 'Inquiry regarding custom addon development', 'customer_name' => 'CV Abadi Jaya', 'customer_email' => 'contact@abadijaya.id', 'priority' => 'Medium', 'status' => 'Pending', 'updated_at' => now()->subDays(1)],
                                (object)['id' => 1039, 'ticket_number' => 'TCK-2026-1039', 'subject' => 'Request to change registered company email address', 'customer_name' => 'Siti Aminah', 'customer_email' => 'siti99@yahoo.com', 'priority' => 'Low', 'status' => 'Resolved', 'updated_at' => now()->subDays(3)]
                            ] as $ticket)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6 text-primary">{{ $ticket->ticket_number }}</div>
                                    <div class="fw-medium text-truncate" style="max-width: 280px;">{{ $ticket->subject }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium">{{ $ticket->customer_name }}</div>
                                    <div class="text-secondary fs-7">{{ $ticket->customer_email }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $prioColors = [
                                            'Urgent' => 'danger',
                                            'High' => 'warning',
                                            'Medium' => 'info',
                                            'Low' => 'secondary',
                                        ];
                                        $color = $prioColors[$ticket->priority ?? 'Medium'] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-3 py-1">{{ $ticket->priority }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusColors = [
                                            'Open' => 'danger',
                                            'In Progress' => 'warning',
                                            'Pending' => 'info',
                                            'Resolved' => 'success',
                                            'Closed' => 'secondary',
                                        ];
                                        $scolor = $statusColors[$ticket->status ?? 'Open'] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $scolor }}-subtle text-{{ $scolor }} border border-{{ $scolor }}-subtle rounded-pill px-3 py-1">{{ $ticket->status }}</span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ is_object($ticket->updated_at ?? null) ? $ticket->updated_at->diffForHumans() : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.tickets.show', $ticket->id ?? 1) }}"><i
                                                        class="bi bi-chat-dots me-2 text-primary"></i> View & Reply</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.tickets.resolve', $ticket->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 text-success">
                                                        <i class="bi bi-check-circle me-2"></i> Mark Resolved
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.tickets.close', $ticket->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 text-secondary">
                                                        <i class="bi bi-x-circle me-2"></i> Close Ticket
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-headset fs-1"></i></div>
                                    <h6 class="fw-medium">No Support Tickets Found</h6>
                                    <p class="fs-7">All clear! No pending customer support tickets.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($tickets) && method_exists($tickets, 'hasPages') && $tickets->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
