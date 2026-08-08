@extends('layouts.admin')

@section('title', 'Vouchers & Discounts — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Vouchers</span>
        </div>
        <h1 class="page-title">Vouchers & Coupons</h1>
        <p class="page-subtitle">Configure promotional discount codes, fixed / percentage discounts, and usage limits.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
            <span>🎟️</span> Create Coupon Code
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Discount Value</th>
                        <th>Max Uses</th>
                        <th>Used Count</th>
                        <th>Status</th>
                        <th>Valid Until</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $v)
                        @php $vObj = is_array($v) ? (object)$v : $v; @endphp
                        <tr>
                            <td><code class="badge badge-purple" style="font-size: 14px;">{{ $vObj->code ?? 'PROMO' }}</code></td>
                            <td class="font-bold text-primary">
                                @if(($vObj->type ?? 'percentage') === 'percentage')
                                    {{ $vObj->discount_percent ?? $vObj->value ?? 10 }}% OFF
                                @else
                                    Rp {{ number_format($vObj->discount_amount ?? $vObj->value ?? 0, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ $vObj->max_uses ?? 'Unlimited' }}</td>
                            <td class="font-semibold">{{ $vObj->used_count ?? 0 }}</td>
                            <td>
                                @if(($vObj->is_active ?? true))
                                    <span class="badge badge-success">ACTIVE</span>
                                @else
                                    <span class="badge badge-muted">INACTIVE</span>
                                @endif
                            </td>
                            <td class="text-xs text-muted">{{ isset($vObj->expires_at) ? \Carbon\Carbon::parse($vObj->expires_at)->format('d M Y') : 'Infinite' }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.vouchers.edit', $vObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">No promotional vouchers active.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
