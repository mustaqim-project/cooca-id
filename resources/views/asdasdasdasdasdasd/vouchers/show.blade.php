@extends('layouts.admin')

@section('title', 'Voucher Details')
@section('subtitle', 'View complete details and usage statistics')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.vouchers.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Vouchers
        </a>
    </div>

    @php
        $isExpired = $voucher->valid_until && \Carbon\Carbon::parse($voucher->valid_until)->isPast();
        $isMaxed = $voucher->max_usage > 0 && $voucher->used_count >= $voucher->max_usage;
        if (!$voucher->is_active) {
            $sBadge = 'neutral';
            $sText = 'Inactive';
        } elseif ($isExpired) {
            $sBadge = 'danger';
            $sText = 'Expired';
        } elseif ($isMaxed) {
            $sBadge = 'warning';
            $sText = 'Fully Used';
        } else {
            $sBadge = 'success';
            $sText = 'Active';
        }
        $usedPct = $voucher->max_usage > 0 ? min(100, ($voucher->used_count / $voucher->max_usage) * 100) : 0;
        $barColor = $usedPct >= 90 ? 'var(--danger)' : ($usedPct >= 75 ? 'var(--warning)' : 'var(--primary)');
    @endphp

    <div class="row g-4">
        {{-- Main --}}
        <div class="col-lg-8">
            {{-- Header card --}}
            <div class="card-saas mb-4">
                <div class="card-saas-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-card-icon purple"
                                style="width:52px;height:52px;border-radius:12px;flex-shrink:0">
                                <i class="bi bi-ticket-perforated" style="font-size:1.3rem"></i>
                            </div>
                            <div>
                                <div class="fw-bold font-monospace" style="font-size:1.4rem;letter-spacing:.1em">
                                    {{ $voucher->code }}</div>
                                <div class="text-muted">{{ $voucher->name }}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge-saas badge-saas-{{ $sBadge }}">{{ $sText }}</span>
                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                class="btn-saas btn-saas-outline btn-saas-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            @if ($voucher->is_active)
                                <form class="form-confirm-submit"
                                    action="{{ route('admin.vouchers.deactivate', $voucher->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-saas btn-saas-sm"
                                        style="background:var(--warning);color:#fff;border:none;border-radius:var(--radius-md);padding:.35rem .75rem;font-size:.82rem;cursor:pointer">
                                        <i class="bi bi-pause-circle me-1"></i> Deactivate
                                    </button>
                                </form>
                            @else
                                <form class="form-confirm-submit"
                                    action="{{ route('admin.vouchers.activate', $voucher->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-saas btn-saas-sm"
                                        style="background:var(--success);color:#fff;border:none;border-radius:var(--radius-md);padding:.35rem .75rem;font-size:.82rem;cursor:pointer">
                                        <i class="bi bi-play-circle me-1"></i> Activate
                                    </button>
                                </form>
                            @endif
                            <form class="form-confirm-delete" action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-saas btn-saas-danger btn-saas-sm">
                                    <i class="bi bi-trash3 me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <div class="card-saas-title">Voucher Information</div>
                </div>
                <div class="card-saas-body p-0">
                    <table class="table mb-0" style="font-size:.9rem">
                        <tbody>
                            <tr>
                                <td class="text-muted ps-4" style="width:35%;padding:.85rem 1rem">Discount Value</td>
                                <td class="fw-semibold pe-4" style="padding:.85rem 1rem">
                                    @if ($voucher->type == 'percentage')
                                        <span style="color:var(--primary);font-size:1.05rem">{{ $voucher->value }}%
                                            OFF</span>
                                        @if ($voucher->max_discount > 0)
                                            <span class="text-muted ms-2" style="font-size:.85rem">(Max Rp
                                                {{ number_format($voucher->max_discount, 0, ',', '.') }})</span>
                                        @endif
                                    @else
                                        <span style="color:var(--primary);font-size:1.05rem">Rp
                                            {{ number_format($voucher->value, 0, ',', '.') }} OFF</span>
                                    @endif
                                </td>
                            </tr>
                            <tr style="background:var(--surface-raised)">
                                <td class="text-muted ps-4" style="padding:.85rem 1rem">Description</td>
                                <td class="pe-4" style="padding:.85rem 1rem">{{ $voucher->description ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-4" style="padding:.85rem 1rem">Minimum Purchase</td>
                                <td class="fw-semibold pe-4" style="padding:.85rem 1rem">
                                    {{ $voucher->min_purchase > 0 ? 'Rp ' . number_format($voucher->min_purchase, 0, ',', '.') : 'None' }}
                                </td>
                            </tr>
                            <tr style="background:var(--surface-raised)">
                                <td class="text-muted ps-4" style="padding:.85rem 1rem">Per-User Limit</td>
                                <td class="fw-semibold pe-4" style="padding:.85rem 1rem">{{ $voucher->per_user_limit }}
                                    time(s)</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-4" style="padding:.85rem 1rem">Applicable To</td>
                                <td class="pe-4" style="padding:.85rem 1rem">
                                    @if (!empty($voucher->applicable_products))
                                        <span class="badge-saas badge-saas-info">Specific products only</span>
                                    @else
                                        All products
                                    @endif
                                </td>
                            </tr>
                            <tr style="background:var(--surface-raised)">
                                <td class="text-muted ps-4" style="padding:.85rem 1rem">Created</td>
                                <td class="pe-4" style="padding:.85rem 1rem">
                                    {{ $voucher->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Usage stats --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <div class="card-saas-title">Usage Statistics</div>
                </div>
                <div class="card-saas-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted" style="font-size:.85rem">Total Uses</span>
                        <span class="fw-bold">{{ $voucher->used_count }} /
                            {{ $voucher->max_usage > 0 ? $voucher->max_usage : 'Unlimited' }}</span>
                    </div>
                    @if ($voucher->max_usage > 0)
                        <div
                            style="height:8px;background:var(--border);border-radius:4px;overflow:hidden;margin-bottom:.5rem">
                            <div
                                style="width:{{ $usedPct }}%;height:100%;background:{{ $barColor }};border-radius:4px;transition:width .4s">
                            </div>
                        </div>
                        <div class="text-muted text-center" style="font-size:.8rem">
                            {{ $voucher->max_usage - $voucher->used_count }} uses remaining</div>
                    @else
                        <div class="text-muted" style="font-size:.8rem">No usage limit set.</div>
                    @endif
                </div>
            </div>

            {{-- Validity --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <div class="card-saas-title">Validity Period</div>
                </div>
                <div class="card-saas-body p-0">
                    <div style="padding:.85rem 1.25rem;border-bottom:1px solid var(--border)">
                        <div class="text-muted mb-1" style="font-size:.8rem">Valid From</div>
                        <div class="fw-semibold">
                            {{ $voucher->valid_from ? \Carbon\Carbon::parse($voucher->valid_from)->format('d M Y, H:i') : 'Immediately' }}
                        </div>
                    </div>
                    <div style="padding:.85rem 1.25rem">
                        <div class="text-muted mb-1" style="font-size:.8rem">Valid Until</div>
                        <div class="fw-semibold {{ $isExpired ? 'text-danger' : '' }}">
                            {{ $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('d M Y, H:i') : 'Never expires' }}
                        </div>
                        @if ($isExpired)
                            <div class="badge-saas badge-saas-danger mt-1">Expired</div>
                        @elseif($voucher->valid_until)
                            <div class="text-muted mt-1" style="font-size:.8rem">
                                {{ \Carbon\Carbon::parse($voucher->valid_until)->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
