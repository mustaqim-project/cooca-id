@extends('affiliator.layouts.app')

@section('title', 'Withdrawal Details')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.withdrawals.index') }}" class="crumb-link">Withdrawals</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details #{{ $withdrawal->id }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <div>
                <div class="page-title">Withdrawal Request #{{ $withdrawal->id }}</div>
                <div class="page-subtitle">Requested on
                    {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</div>
            </div>
        </div>
        <div class="page-actions">
            @php
                $st = strtolower($withdrawal->status ?? 'pending');
                $badgeClass = match ($st) {
                    'completed', 'paid', 'approved' => 'badge-success',
                    'pending' => 'badge-warning',
                    'rejected', 'failed', 'cancelled' => 'badge-danger',
                    default => 'badge-muted',
                };
            @endphp
            <span class="badge {{ $badgeClass }}" style="font-size:13px;padding:6px 14px;">
                {{ ucfirst($st) }}
            </span>
        </div>
    </div>

    <div class="grid-31" style="gap:24px;">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Request Info --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-circle-info"
                            style="color:var(--primary);margin-right:8px;"></i>Request Details</div>
                </div>
                <div class="card-body">
                    <div class="grid-3 mb-4" style="gap:16px;">

                        <div
                            style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">
                                Amount Requested</div>
                            <div style="font-size:22px;font-weight:800;color:var(--text);margin-top:4px;">
                                Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <div
                            style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">
                                Destination Bank</div>
                            <div style="font-size:15px;font-weight:700;color:var(--text);margin-top:4px;">
                                {{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }} -
                                {{ $withdrawal->account_number }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">A/N:
                                {{ $withdrawal->account_name }}</div>
                        </div>

                        <div
                            style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">
                                Request Date</div>
                            <div style="font-size:14px;font-weight:700;color:var(--text);margin-top:4px;">
                                {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>

                    </div>

                    @if ($withdrawal->processed_at)
                        <div class="alert alert-success" style="margin-bottom:12px;">
                            <i class="fa-solid fa-check-circle"></i> Processed on
                            {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y, H:i') }}
                        </div>
                    @endif

                    @if ($withdrawal->notes)
                        <div
                            style="padding:14px;background:var(--bg);border-radius:var(--radius-sm);border:1px solid var(--border);margin-top:12px;">
                            <div
                                style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;">
                                Admin Notes</div>
                            <div style="font-size:13px;color:var(--text);">{{ $withdrawal->notes }}</div>
                        </div>
                    @endif

                    @if ($st === 'rejected')
                        <div class="alert alert-danger" style="margin-top:12px;margin-bottom:0;">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <div>
                                <div style="font-size:11px;font-weight:700;text-transform:uppercase;margin-bottom:4px;">
                                    Rejection Reason</div>
                                <div style="font-size:13px;font-weight:600;">
                                    {{ $withdrawal->reject_reason ?? 'No specific rejection reason provided.' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Status Audit --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-timeline"
                            style="color:var(--accent);margin-right:8px;"></i>Status Audit</div>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"
                                style="background:var(--primary);box-shadow:0 0 0 2px var(--primary);"></div>
                            <div class="timeline-time">
                                {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</div>
                            <div class="timeline-text font-semibold">Request Submitted</div>
                        </div>

                        @if (in_array($st, ['completed', 'paid', 'approved']))
                            <div class="timeline-item">
                                <div class="timeline-dot"
                                    style="background:var(--success);box-shadow:0 0 0 2px var(--success);"></div>
                                <div class="timeline-time">Funds transferred to account</div>
                                <div class="timeline-text font-semibold" style="color:var(--success);">Payout Approved &
                                    Sent</div>
                            </div>
                        @elseif($st === 'rejected')
                            <div class="timeline-item">
                                <div class="timeline-dot"
                                    style="background:var(--danger);box-shadow:0 0 0 2px var(--danger);"></div>
                                <div class="timeline-time">Check rejection reason above</div>
                                <div class="timeline-text font-semibold" style="color:var(--danger);">Request Rejected</div>
                            </div>
                        @else
                            <div class="timeline-item">
                                <div class="timeline-dot"
                                    style="background:var(--border);box-shadow:0 0 0 2px var(--border);"></div>
                                <div class="timeline-time">Pending admin processing</div>
                                <div class="timeline-text font-semibold" style="opacity:.6;">Awaiting Audit</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
