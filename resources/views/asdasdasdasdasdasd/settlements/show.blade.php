@extends('layouts.admin')

@section('title', 'Settlement #{{ $settlement->id }}')
@section('subtitle', 'Withdrawal request details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.settlements.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Settlements
        </a>
    </div>

    <div class="row g-4">
        {{-- Main --}}
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <span class="card-saas-title">Settlement Details</span>
                    @php
                        $s = $settlement->status ?? 'pending';
                        $badge = match ($s) {
                            'paid' => 'success',
                            'approved' => 'info',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'neutral',
                        };
                    @endphp
                    <span class="badge-saas badge-saas-{{ $badge }}">{{ ucfirst($s) }}</span>
                </div>
                <div class="card-saas-body">
                    <table class="table table-sm table-borderless mb-0" style="font-size:0.9rem">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width:35%">Settlement ID</td>
                                <td class="fw-medium font-monospace">#{{ $settlement->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Affiliator</td>
                                <td>
                                    <a href="{{ route('admin.affiliators.show', $settlement->affiliator_id) }}"
                                        class="fw-medium text-decoration-none" style="color:var(--primary)">
                                        {{ $settlement->affiliator->name ?? 'Unknown' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Amount</td>
                                <td class="fw-semibold fs-5">Rp {{ number_format($settlement->amount ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bank Name</td>
                                <td>{{ $settlement->bank_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Number</td>
                                <td class="font-monospace">{{ $settlement->account_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Holder</td>
                                <td>{{ $settlement->account_holder ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Notes</td>
                                <td>{{ $settlement->notes ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Requested At</td>
                                <td>{{ $settlement->created_at?->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                            @if ($settlement->processed_at)
                                <tr>
                                    <td class="text-muted">Processed At</td>
                                    <td>{{ $settlement->processed_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Actions Sidebar --}}
        <div class="col-lg-4">
            <div class="card-saas mb-4">
                <div class="card-saas-header"><span class="card-saas-title">Actions</span></div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    @if ($settlement->status === 'pending')
                        <form class="form-confirm-submit"
                            action="{{ route('admin.settlements.approve', $settlement->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Approve
                            </button>
                        </form>
                        <form class="form-confirm-submit" action="{{ route('admin.settlements.reject', $settlement->id) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-danger w-100">
                                <i class="bi bi-x-lg me-1"></i> Reject
                            </button>
                        </form>
                    @endif

                    @if ($settlement->status === 'approved')
                        <form class="form-confirm-submit"
                            action="{{ route('admin.settlements.markAsPaid', $settlement->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-cash-coin me-1"></i> Mark as Paid
                            </button>
                        </form>
                    @endif

                    @if (in_array($settlement->status, ['paid', 'rejected']))
                        <div class="text-muted text-center" style="font-size:0.85rem">No actions available for this status.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-saas">
                <div class="card-saas-header"><span class="card-saas-title">Affiliator</span></div>
                <div class="card-saas-body">
                    <div class="fw-semibold">{{ $settlement->affiliator->name ?? '-' }}</div>
                    <div class="text-muted" style="font-size:0.85rem">{{ $settlement->affiliator->email ?? '' }}</div>
                    @if ($settlement->affiliator_id)
                        <div class="mt-3">
                            <a href="{{ route('admin.affiliators.show', $settlement->affiliator_id) }}"
                                class="btn-saas btn-saas-ghost btn-saas-sm">
                                View Profile <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
