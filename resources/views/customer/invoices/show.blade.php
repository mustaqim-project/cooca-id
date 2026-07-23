@extends('customer.layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.invoices.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Invoice Details</h2>
                    <p class="text-secondary mb-0">View invoice details and payment status.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <!-- Invoice Header -->
                <div class="card-header bg-transparent border-bottom border-light p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
                        <div>
                            <h2 class="fw-bold mb-1">INVOICE</h2>
                            <p class="text-secondary mb-0">#{{ $invoice->invoice_number }}</p>
                        </div>
                        
                        <div>
                            @php
                                $statusClass = match($invoice->status) {
                                    'paid' => 'success',
                                    'unpaid' => ($invoice->due_date && $invoice->due_date->isPast()) ? 'danger' : 'warning',
                                    'cancelled' => 'secondary',
                                    default => 'secondary'
                                };
                                
                                $statusText = ($invoice->status == 'unpaid' && $invoice->due_date && $invoice->due_date->isPast()) 
                                            ? 'OVERDUE' 
                                            : strtoupper($invoice->status);
                            @endphp
                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-4 py-2 fs-6">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="text-secondary text-uppercase fw-semibold mb-3 fs-7 tracking-wider">Billed To</h6>
                            <address class="mb-0">
                                <strong class="d-block mb-1">{{ auth()->guard('customer')->user()->name }}</strong>
                                @if(auth()->guard('customer')->user()->business_name)
                                    <span class="d-block text-secondary">{{ auth()->guard('customer')->user()->business_name }}</span>
                                @endif
                                <span class="d-block text-secondary">{{ auth()->guard('customer')->user()->email }}</span>
                                @if(auth()->guard('customer')->user()->phone)
                                    <span class="d-block text-secondary">{{ auth()->guard('customer')->user()->phone }}</span>
                                @endif
                            </address>
                        </div>
                        
                        <div class="col-md-6 text-md-end">
                            <div class="mb-3">
                                <h6 class="text-secondary text-uppercase fw-semibold mb-1 fs-7 tracking-wider">Date Issued</h6>
                                <p class="mb-0 fw-medium">{{ $invoice->created_at->format('F d, Y') }}</p>
                            </div>
                            @if($invoice->status == 'unpaid' && $invoice->due_date)
                            <div>
                                <h6 class="text-secondary text-uppercase fw-semibold mb-1 fs-7 tracking-wider">Due Date</h6>
                                <p class="mb-0 fw-medium {{ $invoice->due_date->isPast() ? 'text-danger' : '' }}">
                                    {{ $invoice->due_date->format('F d, Y') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Invoice Items -->
                <div class="card-body p-4 p-md-5">
                    <h6 class="text-secondary text-uppercase fw-semibold mb-4 fs-7 tracking-wider">Invoice Items</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="border-bottom border-light">
                                <tr>
                                    <th class="text-secondary text-uppercase fs-7 pb-3 px-0">Description</th>
                                    <th class="text-secondary text-uppercase fs-7 pb-3 px-0 text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="border-bottom border-light">
                                <tr>
                                    <td class="py-4 px-0">
                                        <div class="fw-semibold">
                                            {{ $invoice->subscription->plan->product->name ?? 'Subscription Plan' }}
                                        </div>
                                        @if($invoice->subscription && $invoice->subscription->plan)
                                            <div class="text-secondary fs-7 mt-1">
                                                {{ $invoice->subscription->plan->name }} ({{ $invoice->subscription->plan->billing_cycle ?? 'Monthly' }})
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-0 text-end fw-medium">
                                        Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                @if($invoice->discount > 0)
                                <tr>
                                    <td class="pt-4 pb-2 px-0 text-end text-secondary fs-7">Discount</td>
                                    <td class="pt-4 pb-2 px-0 text-end text-success fw-medium">
                                        - Rp {{ number_format($invoice->discount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                @if($invoice->tax > 0)
                                <tr>
                                    <td class="py-2 px-0 text-end text-secondary fs-7">Tax</td>
                                    <td class="py-2 px-0 text-end fw-medium">
                                        Rp {{ number_format($invoice->tax, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="py-4 px-0 text-end fw-bold fs-6">Total</td>
                                    <td class="py-4 px-0 text-end fw-bolder text-primary fs-5">
                                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Payment Information -->
                @if($invoice->transaction)
                <div class="card-body p-4 p-md-5 bg-light border-top border-bottom border-light">
                    <h6 class="text-secondary text-uppercase fw-semibold mb-4 fs-7 tracking-wider">Payment Details</h6>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="text-secondary fs-7 mb-1">Payment Method</div>
                            <div class="fw-semibold">{{ strtoupper($invoice->transaction->payment_type ?? '-') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-secondary fs-7 mb-1">Transaction ID</div>
                            <div class="font-monospace text-dark">{{ $invoice->transaction->id }}</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="card-footer bg-transparent border-top-0 p-4 p-md-5 d-flex justify-content-between align-items-center">
                    <a href="{{ route('customer.invoices.download', $invoice->id) }}" class="btn btn-outline-secondary rounded-pill px-4 hover-lift">
                        <i class="bi bi-download me-2"></i> Download PDF
                    </a>
                    
                    @if($invoice->status == 'unpaid')
                        <a href="{{ route('customer.payments.show', $invoice->transaction->id ?? 0) }}" class="btn btn-primary rounded-pill px-5 py-2 hover-lift fw-medium">
                            Pay Now <i class="bi bi-credit-card ms-2"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
