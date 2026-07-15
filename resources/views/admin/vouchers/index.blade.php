@extends('layouts.admin')

@section('title', 'Vouchers & Discounts')
@section('subtitle', 'Manage promotional codes and discounts')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search code, name...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.vouchers.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Voucher
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="vouchersTable">
                    <thead>
                        <tr>
                            <th>Code / Name</th>
                            <th>Discount</th>
                            <th>Usage</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $voucher)
                            @php
                                $isExpired =
                                    $voucher->valid_until && \Carbon\Carbon::parse($voucher->valid_until)->isPast();
                                $isMaxed = $voucher->max_usage > 0 && $voucher->used_count >= $voucher->max_usage;
                                $usagePct =
                                    $voucher->max_usage > 0
                                        ? min(100, ($voucher->used_count / $voucher->max_usage) * 100)
                                        : 0;
                                $barColor =
                                    $usagePct >= 90
                                        ? 'var(--danger)'
                                        : ($usagePct >= 75
                                            ? 'var(--warning)'
                                            : 'var(--success)');

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
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-card-icon purple"
                                            style="width:36px;height:36px;border-radius:8px;flex-shrink:0">
                                            <i class="bi bi-ticket-perforated" style="font-size:.9rem"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold font-monospace" style="letter-spacing:.05em">
                                                {{ $voucher->code }}</div>
                                            <div class="text-muted" style="font-size:.8rem">{{ $voucher->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        @if ($voucher->type == 'percentage')
                                            {{ $voucher->value }}% OFF
                                        @else
                                            Rp {{ number_format($voucher->value, 0, ',', '.') }} OFF
                                        @endif
                                    </div>
                                    @if ($voucher->min_purchase > 0)
                                        <div class="text-muted" style="font-size:.8rem">Min: Rp
                                            {{ number_format($voucher->min_purchase, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="width:80px;height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                                            <div
                                                style="width:{{ $usagePct }}%;height:100%;background:{{ $barColor }};border-radius:3px">
                                            </div>
                                        </div>
                                        <span style="font-size:.82rem;color:var(--text-muted)">
                                            {{ $voucher->used_count }} /
                                            {{ $voucher->max_usage > 0 ? $voucher->max_usage : '∞' }}
                                        </span>
                                    </div>
                                </td>
                                <td style="font-size:.85rem">
                                    @if ($voucher->valid_from)
                                        <div class="text-muted">From:
                                            {{ \Carbon\Carbon::parse($voucher->valid_from)->format('d M Y') }}</div>
                                    @endif
                                    @if ($voucher->valid_until)
                                        <div class="{{ $isExpired ? 'text-danger fw-semibold' : '' }}">
                                            To: {{ \Carbon\Carbon::parse($voucher->valid_until)->format('d M Y') }}
                                        </div>
                                    @else
                                        <div class="text-muted">Never expires</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-saas badge-saas-{{ $sBadge }}">{{ $sText }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.vouchers.show', $voucher->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($voucher->is_active)
                                            <form class="form-confirm-submit"
                                                action="{{ route('admin.vouchers.deactivate', $voucher->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon"
                                                    title="Deactivate" style="color:var(--warning)">
                                                    <i class="bi bi-pause-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form class="form-confirm-submit"
                                                action="{{ route('admin.vouchers.activate', $voucher->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon"
                                                    title="Activate" style="color:var(--success)">
                                                    <i class="bi bi-play-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form class="form-confirm-delete"
                                            action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon"
                                                title="Delete" style="color:var(--danger)">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-ticket-perforated"></i></div>
                                        <div class="empty-state-title">No vouchers found</div>
                                        <div class="empty-state-description">Create your first promotional code.</div>
                                        <a href="{{ route('admin.vouchers.create') }}"
                                            class="btn-saas btn-saas-primary mt-3">
                                            <i class="bi bi-plus-lg me-1"></i> Create Voucher
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#vouchersTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
