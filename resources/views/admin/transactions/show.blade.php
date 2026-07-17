@extends('admin.layouts.app')

@section('title', 'Transactions Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.transactions.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Transaction Details</h2>
                    <p class="text-secondary mb-0">View full transaction and payment information.</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-receipt fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $transaction->invoice_number }}</h4>
                    <p class="text-secondary mb-3">Amount: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </p>
                    <div>
                        @if ($transaction->status == 'paid')
                            <span
                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle text-capitalize">{{ $transaction->status }}</span>
                        @elseif($transaction->status == 'pending')
                            <span
                                class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle text-capitalize">{{ $transaction->status }}</span>
                        @elseif($transaction->status == 'failed' || $transaction->status == 'cancelled')
                            <span
                                class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 border border-danger-subtle text-capitalize">{{ $transaction->status }}</span>
                        @else
                            <span
                                class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle text-capitalize">{{ $transaction->status }}</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Payment Method</span>
                        <span class="fw-medium fs-7 text-uppercase">{{ $transaction->payment_method ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Payment Channel</span>
                        <span class="fw-medium fs-7">{{ $transaction->payment_channel ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span class="fw-medium fs-7">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Paid At</span>
                        <span
                            class="fw-medium fs-7">{{ $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('d M Y, H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass mb-4">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Customer Name</label>
                                <div class="fw-medium">
                                    {{ $transaction->customer->name ?? ($transaction->user->name ?? 'N/A') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Email Address</label>
                                <div class="fw-medium">
                                    {{ $transaction->customer->email ?? ($transaction->user->email ?? 'N/A') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Phone Number</label>
                                <div class="fw-medium">
                                    {{ $transaction->customer->phone ?? ($transaction->user->phone ?? 'N/A') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">User / Customer ID</label>
                                <div class="fw-medium">#{{ $transaction->user_id ?? ($transaction->customer_id ?? 'N/A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                                    <tr>
                                        <th class="py-3 px-4">Item</th>
                                        <th class="py-3 px-3 text-center">Qty</th>
                                        <th class="py-3 px-3 text-end">Price</th>
                                        <th class="py-3 px-4 text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Main Product -->
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="fw-medium">{{ $transaction->product->name ?? 'Product Name' }}
                                            </div>
                                            <div class="text-secondary fs-7">Package Subscription</div>
                                        </td>
                                        <td class="py-3 px-3 text-center">1</td>
                                        <td class="py-3 px-3 text-end">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-end fw-medium">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="3" class="py-3 px-4 text-end text-secondary fs-7">Subtotal</td>
                                        <td class="py-3 px-4 text-end fw-medium">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-end text-secondary fs-7">Discount / Voucher
                                        </td>
                                        <td class="py-2 px-4 text-end text-success">- Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-end text-secondary fs-7">Tax</td>
                                        <td class="py-2 px-4 text-end">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-3 px-4 text-end fw-bold fs-6">Total Amount</td>
                                        <td class="py-3 px-4 text-end fw-bold fs-6 text-primary">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
