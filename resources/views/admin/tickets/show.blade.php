@extends('layouts.admin')

@section('title', 'Ticket Details')
@section('subtitle', '#{{ $ticket->id }} – {{ $ticket->subject }}')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.tickets.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Tickets
        </a>
    </div>

    <div class="row g-4">
        {{-- Left: ticket info + replies --}}
        <div class="col-lg-8">

            {{-- Info card --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="card-saas-title mb-0">{{ $ticket->subject }}</h5>
                    <div class="d-flex gap-2">
                        @php
                            $priorityMap = [
                                'urgent' => 'danger',
                                'high' => 'warning',
                                'medium' => 'info',
                                'low' => 'neutral',
                            ];
                            $statusMap = [
                                'open' => 'danger',
                                'in_progress' => 'warning',
                                'resolved' => 'success',
                                'closed' => 'neutral',
                            ];
                            $statusLabel = match ($ticket->status) {
                                'in_progress' => 'In Progress',
                                default => ucfirst($ticket->status),
                            };
                        @endphp
                        <span
                            class="badge-saas badge-saas-{{ $priorityMap[$ticket->priority] ?? 'neutral' }}">{{ ucfirst($ticket->priority) }}</span>
                        <span
                            class="badge-saas badge-saas-{{ $statusMap[$ticket->status] ?? 'neutral' }}">{{ $statusLabel }}</span>
                    </div>
                </div>
                <div class="card-saas-body p-0">
                    <table class="table mb-0" style="font-size:.9rem">
                        <tbody>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal" style="width:30%">Ticket ID</th>
                                <td class="pe-4 py-3">#{{ $ticket->id }}</td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal">Customer</th>
                                <td class="pe-4 py-3">
                                    <a href="{{ route('admin.customers.show', $ticket->customer_id ?? 0) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $ticket->customer->name ?? 'N/A' }}
                                    </a>
                                    <div class="small text-muted">{{ $ticket->customer->email ?? '' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal">Assigned To</th>
                                <td class="pe-4 py-3">
                                    @if ($ticket->assignedTo)
                                        <span class="badge-saas badge-saas-primary">{{ $ticket->assignedTo->name }}</span>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal">Created</th>
                                <td class="pe-4 py-3">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-normal align-top">Message</th>
                                <td class="pe-4 py-3" style="white-space:pre-line">{{ $ticket->message }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Replies --}}
            @if ($ticket->replies && $ticket->replies->count() > 0)
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title mb-0"><i class="bi bi-chat-left-text me-2"></i>Replies
                            ({{ $ticket->replies->count() }})</h5>
                    </div>
                    <div class="card-saas-body">
                        @foreach ($ticket->replies as $reply)
                            <div class="mb-4 pb-4 @if (!$loop->last) border-bottom @endif">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="stat-card-icon {{ $reply->is_admin ? 'blue' : 'purple' }}"
                                        style="width:32px;height:32px;min-width:32px;font-size:.75rem">
                                        {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $reply->user->name ?? 'Unknown' }}
                                            @if ($reply->is_admin)
                                                <span class="badge-saas badge-saas-info ms-1"
                                                    style="font-size:.65rem">Admin</span>
                                            @endif
                                        </div>
                                        <div class="text-muted" style="font-size:.75rem">
                                            {{ \Carbon\Carbon::parse($reply->created_at)->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                                <div class="ps-5" style="white-space:pre-line;font-size:.9rem">{{ $reply->message }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Reply form --}}
            @if (!in_array($ticket->status, ['resolved', 'closed']))
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title mb-0"><i class="bi bi-reply me-2"></i>Reply</h5>
                    </div>
                    <div class="card-saas-body">
                        <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                            @csrf
                            <div class="form-saas-group">
                                <label class="form-saas-label" for="replyMessage">Message</label>
                                <textarea class="form-saas-textarea @error('message') is-invalid @enderror" id="replyMessage" name="message"
                                    rows="5" placeholder="Type your reply...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn-saas btn-saas-primary">
                                    <i class="bi bi-send me-1"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right: actions sidebar --}}
        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-3">

                    @if ($ticket->status !== 'resolved')
                        <form action="{{ route('admin.tickets.resolve', $ticket) }}" method="POST"
                            class="form-confirm-submit">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-circle me-2"></i>Mark as Resolved
                            </button>
                        </form>
                    @endif

                    @if ($ticket->status !== 'closed')
                        <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST"
                            class="form-confirm-submit">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-secondary w-100">
                                <i class="bi bi-lock me-2"></i>Close Ticket
                            </button>
                        </form>
                    @endif

                    @if (in_array($ticket->status, ['resolved', 'closed']))
                        <div class="text-center text-muted small py-2">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            Ticket is {{ $ticket->status }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection
