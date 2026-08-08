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
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-s btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
            <h2 style="font-size:20px;font-weight:800;color:var(--text);">Withdrawal Request #{{ $withdrawal->id }}</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Requested on {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</p>
        </div>
    </div>
    <div>
        @php
            $st = strtolower($withdrawal->status ?? 'pending');
            $badgeClass = match($st) {
                'completed', 'paid', 'approved' => 'status-paid',
                'pending'                       => 'status-pending',
                'rejected', 'failed', 'cancelled' => 'status-cancelled',
                default                         => 'status-issued',
            };
        @endphp
        <span class="badge-status {{ $badgeClass }}" style="font-size:13px;padding:6px 14px;">
            {{ ucfirst($st) }}
        </span>
    </div>
</div>

<div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));gap:24px;">

    {{-- Left Card: Request Info --}}
    <div class="portal-card" style="grid-column: span 2;">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-circle-info" style="color:var(--primary);"></i>
                Request Details
            </div>
        </div>
        <div class="portal-card-body">
            <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;" class="mb-4">

                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Amount Requested</div>
                    <div style="font-size:22px;font-weight:800;color:var(--text);margin-top:4px;">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </div>
                </div>

                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Destination Bank</div>
                    <div style="font-size:15px;font-weight:700;color:var(--text);margin-top:4px;">
                        {{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }} - {{ $withdrawal->account_number }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">A/N: {{ $withdrawal->account_name }}</div>
                </div>

                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Request Date</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text);margin-top:4px;">
                        {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}
                    </div>
                </div>

            </div>

            @if($withdrawal->processed_at)
                <div style="padding:12px 16px;background:rgba(16,185,129,.1);border:1px solid var(--success);border-radius:var(--radius-sm);color:var(--success);font-size:13px;font-weight:600;margin-bottom:12px;">
                    <i class="fa-solid fa-check-circle" style="margin-right:6px;"></i> Processed on {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y, H:i') }}
                </div>
            @endif

            @if($withdrawal->notes)
                <div style="padding:14px;background:var(--bg);border-radius:var(--radius-sm);border:1px solid var(--border);margin-top:12px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;">Admin Notes</div>
                    <div style="font-size:13px;color:var(--text);">{{ $withdrawal->notes }}</div>
                </div>
            @endif

            @if($st === 'rejected')
                <div style="padding:14px;background:rgba(239,68,68,.1);border-radius:var(--radius-sm);border:1px solid var(--danger);margin-top:12px;color:var(--danger);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;margin-bottom:4px;">Rejection Reason</div>
                    <div style="font-size:13px;font-weight:600;">{{ $withdrawal->reject_reason ?? 'No specific rejection reason provided.' }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Card: Status Timeline --}}
    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-timeline" style="color:var(--accent);"></i>
                Status Audit
            </div>
        </div>
        <div class="portal-card-body">
            <div style="display:flex;flex-direction:column;gap:16px;position:relative;padding-left:8px;">

                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <div style="width:24px;height:24px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;">Request Submitted</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                @if(in_array($st, ['completed', 'paid', 'approved']))
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <div style="width:24px;height:24px;background:var(--success);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--success);">Payout Approved & Sent</div>
                            <div style="font-size:11px;color:var(--text-muted);">Funds transferred to account</div>
                        </div>
                    </div>
                @elseif($st === 'rejected')
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <div style="width:24px;height:24px;background:var(--danger);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--danger);">Request Rejected</div>
                            <div style="font-size:11px;color:var(--text-muted);">Check rejection reason above</div>
                        </div>
                    </div>
                @else
                    <div style="display:flex;align-items:flex-start;gap:12px;opacity:.6;">
                        <div style="width:24px;height:24px;background:var(--border);color:var(--text-muted);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;">Awaiting Audit</div>
                            <div style="font-size:11px;color:var(--text-muted);">Pending admin processing</div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>
@endsection
