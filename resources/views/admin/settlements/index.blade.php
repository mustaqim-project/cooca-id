@extends('admin.layouts.app')

@section('title', 'Settlements')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Settlements</h2>
                <p class="text-secondary mb-0">Manage affiliator withdrawal requests and payouts.</p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button
                        class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary dropdown-toggle"
                        type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-filter me-2"></i> Filter Status
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 glass">
                        <li><a class="dropdown-item py-2" href="{{ route('admin.settlements.index') }}">All Status</a></li>
                        <li><a class="dropdown-item py-2"
                                href="{{ route('admin.settlements.index', ['status' => 'pending']) }}">Pending</a></li>
                        <li><a class="dropdown-item py-2"
                                href="{{ route('admin.settlements.index', ['status' => 'processing']) }}">Processing</a>
                        </li>
                        <li><a class="dropdown-item py-2"
                                href="{{ route('admin.settlements.index', ['status' => 'completed']) }}">Completed</a></li>
                        <li><a class="dropdown-item py-2"
                                href="{{ route('admin.settlements.index', ['status' => 'failed']) }}">Failed/Rejected</a>
                        </li>
                    </ul>
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
                        placeholder="Search by TRW ID or Affiliator...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Reference ID</th>
                            <th class="py-3 px-3 border-0">Affiliator</th>
                            <th class="py-3 px-3 border-0">Amount</th>
                            <th class="py-3 px-3 border-0">Bank/E-Wallet</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Date Requested</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($settlements ?? [] as $settlement)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-primary">
                                    {{ $settlement->reference_id ?? 'TRW-' . strtoupper(Str::random(8)) }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ substr($settlement->affiliator->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">
                                                {{ $settlement->affiliator->name ?? 'Unknown Affiliator' }}</div>
                                            <div class="text-secondary fs-7">
                                                {{ $settlement->affiliator->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 fw-bold">
                                    Rp {{ number_format($settlement->amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium">{{ $settlement->bank_name ?? 'BCA' }}</div>
                                    <div class="text-secondary fs-7">{{ $settlement->account_number ?? '1234567890' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $status = $settlement->status ?? 'pending';
                                        $badgeClass = match ($status) {
                                            'completed' => 'bg-success-subtle text-success border-success-subtle',
                                            'processing' => 'bg-info-subtle text-info border-info-subtle',
                                            'failed', 'rejected' => 'bg-danger-subtle text-danger border-danger-subtle',
                                            default => 'bg-warning-subtle text-warning border-warning-subtle',
                                        };
                                    @endphp
                                    <span
                                        class="badge {{ $badgeClass }} border rounded-pill px-3 py-1 text-capitalize">{{ $status }}</span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $settlement->created_at ? $settlement->created_at->format('d M Y, H:i') : 'Oct 15, 2026' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.settlements.show', $settlement->id ?? 1) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> Review Details</a></li>

                                            @if (in_array($status, ['pending', 'processing']))
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.settlements.approve', $settlement->id ?? 1) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-success">
                                                            <i class="bi bi-check-circle me-2"></i> Mark as Completed
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.settlements.reject', $settlement->id ?? 1) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                                            <i class="bi bi-x-circle me-2"></i> Reject Request
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-wallet2 fs-1"></i></div>
                                    <h6 class="fw-medium">No Settlement Requests</h6>
                                    <p class="fs-7">There are no withdrawal requests from affiliators at the moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($settlements) && method_exists($settlements, 'hasPages') && $settlements->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $settlements->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
