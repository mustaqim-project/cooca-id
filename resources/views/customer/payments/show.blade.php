@extends('customer.layouts.app')

@section('title', 'Payment Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.payments.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Payment Details</h2>
                    <p class="text-secondary mb-0">View details for transaction {{ $payment->invoice_number ?? $payment->id }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <!-- Status Header -->
                <div class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-shield-check me-2"></i> Transaction Status</h5>
                    
                    <div>
                        @php
                            $statusClass = match($payment->status) {
                                'paid', 'settlement', 'capture' => 'success',
                                'pending' => 'warning',
                                'failed', 'deny', 'cancel', 'expire' => 'danger',
                                'refunded' => 'info',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-4 py-2 fs-6">
                            {{ strtoupper($payment->status) }}
                        </span>
                    </div>
                </div>
                
                <!-- Details Body -->
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Transaction ID</div>
                            <div class="fw-semibold text-dark font-monospace">{{ $payment->id }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Order ID / Invoice</div>
                            <div class="fw-semibold text-dark font-monospace">{{ $payment->invoice_number ?? '-' }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Gross Amount</div>
                            <div class="fw-bolder text-primary fs-5">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Payment Method</div>
                            <div class="text-dark">
                                {{ strtoupper($payment->payment_type ?? 'Waiting for payment') }}
                                @if($payment->bank)
                                    <span class="text-secondary ms-1">({{ $payment->bank }})</span>
                                @endif
                            </div>
                        </li>
                        @if($payment->payment_url && $payment->status == 'pending')
                            <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                                <div class="fw-medium text-secondary" style="width: 250px;">Payment Link</div>
                                <div class="text-dark">
                                    <a href="{{ $payment->payment_url }}" target="_blank" class="text-primary hover-lift text-decoration-none">
                                        {{ $payment->payment_url }}
                                    </a>
                                </div>
                            </li>
                        @endif
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Created At</div>
                            <div class="text-dark">{{ $payment->created_at->format('F d, Y - H:i:s') }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Last Updated</div>
                            <div class="text-dark">{{ $payment->updated_at->format('F d, Y - H:i:s') }}</div>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                @if($payment->status == 'pending' && $payment->payment_url)
                    <div class="card-footer bg-transparent border-top border-light p-4 d-flex justify-content-end">
                        <a href="{{ $payment->payment_url }}" class="btn btn-primary rounded-pill px-4 py-2 hover-lift fw-medium">
                            <i class="bi bi-credit-card me-2"></i> Complete Payment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
