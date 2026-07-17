@extends('admin.layouts.app')

@section('title', 'Voucher Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.vouchers.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Voucher Details</h2>
                    <p class="text-secondary mb-0">View comprehensive voucher performance and usage.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>
                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm"
                        onclick="return confirm('Are you sure you want to delete this voucher?');">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-ticket-detailed fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-primary font-monospace">{{ $voucher->code }}</h3>
                    <p class="text-secondary mb-3">Voucher ID: #{{ $voucher->id }}</p>
                    <div>
                        @if ($voucher->is_active ?? true)
                            @if ($voucher->expires_at && \Carbon\Carbon::parse($voucher->expires_at)->isPast())
                                <span
                                    class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 border border-danger-subtle">Expired</span>
                            @elseif($voucher->max_uses && ($voucher->used_count ?? 0) >= $voucher->max_uses)
                                <span
                                    class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle">Fully
                                    Used</span>
                            @else
                                <span
                                    class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Active</span>
                            @endif
                        @else
                            <span
                                class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle">Inactive</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span
                            class="fw-medium fs-7">{{ $voucher->created_at ? $voucher->created_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span
                            class="fw-medium fs-7">{{ $voucher->updated_at ? $voucher->updated_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Valid Until</span>
                        <span
                            class="fw-medium fs-7 {{ $voucher->expires_at && \Carbon\Carbon::parse($voucher->expires_at)->isPast() ? 'text-danger' : '' }}">
                            {{ $voucher->expires_at ? \Carbon\Carbon::parse($voucher->expires_at)->format('d M Y') : 'No Expiry' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Voucher Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6 col-md-4">
                                <div class="p-3 bg-light rounded-4 border">
                                    <label class="text-secondary fs-7 mb-1 d-block"><i class="bi bi-tag me-1"></i> Discount
                                        Type</label>
                                    <div class="fw-bold fs-5 text-capitalize">{{ $voucher->type ?? 'Percentage' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="p-3 bg-light rounded-4 border">
                                    <label class="text-secondary fs-7 mb-1 d-block"><i
                                            class="bi bi-currency-dollar me-1"></i> Discount Value</label>
                                    <div class="fw-bold fs-5 text-primary">
                                        @if (($voucher->type ?? '') == 'fixed')
                                            Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                        @else
                                            {{ $voucher->value }}%
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="p-3 bg-light rounded-4 border">
                                    <label class="text-secondary fs-7 mb-1 d-block"><i class="bi bi-people me-1"></i> Total
                                        Usage</label>
                                    <div class="fw-bold fs-5">
                                        {{ $voucher->used_count ?? 0 }} <span class="text-secondary fs-6 fw-normal">/
                                            {{ $voucher->max_uses ?? '∞' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 mt-4 pt-2 border-top">Usage Performance</h6>

                        <!-- Progress Bar for Usage -->
                        @if ($voucher->max_uses)
                            @php
                                $usagePercent = min(
                                    100,
                                    round((($voucher->used_count ?? 0) / $voucher->max_uses) * 100),
                                );
                                $pgClass =
                                    $usagePercent > 90
                                        ? 'bg-danger'
                                        : ($usagePercent > 75
                                            ? 'bg-warning'
                                            : 'bg-primary');
                            @endphp
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span class="fs-7 fw-medium">Quota Used</span>
                                <span class="fs-7 fw-bold">{{ $usagePercent }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $pgClass }} rounded-pill" role="progressbar"
                                    style="width: {{ $usagePercent }}%" aria-valuenow="{{ $usagePercent }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-secondary fs-7 mt-2 mb-0">
                                {{ $voucher->max_uses - ($voucher->used_count ?? 0) }} remaining uses out of
                                {{ $voucher->max_uses }} total quota.
                            </p>
                        @else
                            <div
                                class="d-flex align-items-center gap-3 p-3 bg-primary-subtle text-primary rounded-3 border border-primary-subtle">
                                <i class="bi bi-infinity fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Unlimited Usage</h6>
                                    <p class="fs-7 mb-0">This voucher does not have a maximum usage limit.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
