@extends('admin.layouts.app')

@section('title', 'Settlement Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.settlements.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Settlement Details</h2>
                    <p class="text-secondary mb-0">Review withdrawal request and update status.</p>
                </div>
            </div>
            @if (in_array($settlement->status ?? 'pending', ['pending', 'processing']))
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.settlements.reject', $settlement->id ?? 1) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm">
                            <i class="bi bi-x-circle me-2"></i> Reject
                        </button>
                    </form>
                    <form action="{{ route('admin.settlements.approve', $settlement->id ?? 1) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 hover-lift shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Complete Transfer
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-primary">Rp
                        {{ number_format($settlement->amount ?? 1500000, 0, ',', '.') }}</h3>
                    <p class="text-secondary mb-3 font-monospace">Ref:
                        {{ $settlement->reference_id ?? 'TRW-' . strtoupper(Str::random(8)) }}</p>
                    <div>
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
                            class="badge {{ $badgeClass }} rounded-pill px-4 py-2 border text-capitalize fs-6">{{ $status }}</span>
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Requested At</span>
                        <span
                            class="fw-medium fs-7">{{ $settlement->created_at ? $settlement->created_at->format('d M Y, H:i') : 'Oct 15, 2026, 10:30' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span
                            class="fw-medium fs-7">{{ $settlement->updated_at ? $settlement->updated_at->format('d M Y, H:i') : 'Oct 15, 2026, 10:30' }}</span>
                    </div>
                    @if ($status === 'completed')
                        <div class="d-flex justify-content-between text-start">
                            <span class="text-secondary fs-7">Processed By</span>
                            <span class="fw-medium fs-7">{{ $settlement->processed_by_name ?? 'Admin' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 mb-4">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Affiliator Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4"
                                style="width: 60px; height: 60px;">
                                {{ substr($settlement->affiliator->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $settlement->affiliator->name ?? 'Unknown Affiliator' }}</h5>
                                <p class="text-secondary mb-0">
                                    {{ $settlement->affiliator->email ?? 'no-email@example.com' }}</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 ms-auto">
                                View Profile
                            </button>
                        </div>

                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-4 border">
                                    <label class="text-secondary fs-7 mb-1 d-block"><i class="bi bi-person-badge me-1"></i>
                                        Affiliator ID</label>
                                    <div class="fw-medium font-monospace">
                                        AFF-{{ str_pad($settlement->affiliator_id ?? 1, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-4 border">
                                    <label class="text-secondary fs-7 mb-1 d-block"><i class="bi bi-link-45deg me-1"></i>
                                        Referral Code</label>
                                    <div class="fw-medium font-monospace">
                                        {{ $settlement->affiliator->referral_code ?? 'AFFCODE' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Transfer Destination</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Bank / E-Wallet Name</label>
                                <div class="fw-bold fs-5">{{ $settlement->bank_name ?? 'Bank Central Asia (BCA)' }}</div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Account Number</label>
                                <div class="fw-bold fs-5 font-monospace text-primary">
                                    {{ $settlement->account_number ?? '1234567890' }}
                                    <button class="btn btn-sm btn-light border-0 text-secondary ms-2" title="Copy"><i
                                            class="bi bi-copy"></i></button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="text-secondary fs-7 mb-1 d-block">Account Holder Name</label>
                                <div class="fw-bold fs-5">{{ $settlement->account_holder ?? 'John Doe' }}</div>
                            </div>

                            @if ($settlement->notes ?? false)
                                <div class="col-12">
                                    <label class="text-secondary fs-7 mb-1 d-block">Additional Notes</label>
                                    <div class="p-3 bg-light rounded-3 border text-secondary">
                                        {{ $settlement->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if (in_array($status, ['pending', 'processing']))
                            <div class="alert alert-info border-info-subtle mt-4 mb-0 rounded-4 d-flex gap-3">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Manual Transfer Required</h6>
                                    <p class="mb-0 fs-7">Please process the transfer manually to the account details
                                        provided above outside of this system. Once completed, upload the proof of transfer
                                        (optional) and click "Complete Transfer".</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
