@extends('customer.layouts.app')

@section('title', 'Payment Pending')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden text-center p-5">
                <div class="d-flex justify-content-center mb-4">
                    <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                    </div>
                </div>
                
                <h2 class="fw-bolder mb-3">Payment is Pending</h2>
                <p class="text-secondary mb-5 fs-6">
                    We are waiting for your payment to be completed. If you chose bank transfer, please complete the transfer.
                </p>
                
                <div class="d-flex justify-content-center">
                    <a href="{{ route('customer.payments.index') }}" class="btn btn-primary rounded-pill px-5 py-2 hover-lift fw-medium">
                        Check Payment Status
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection