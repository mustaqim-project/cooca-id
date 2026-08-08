@extends('layouts.customer')
@section('title', 'Payment Successful')
@section('content')
<div class="card" style="max-width:540px;margin:40px auto;text-align:center;padding:40px 24px;">
    <div style="width:72px;height:72px;border-radius:50%;background:rgba(var(--success-rgb),.15);color:var(--success);font-size:36px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <i class="fa-solid fa-circle-check"></i>
    </div>
    <h2 class="font-bold text-2xl mb-2">Payment Received!</h2>
    <p class="text-sm text-muted mb-6">Thank you. Your transaction has been processed and your services are being provisioned.</p>
    <div class="flex gap-3 justify-center">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
        <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline">View Invoices</a>
    </div>
</div>
@endsection
