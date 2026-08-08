@extends('layouts.customer')
@section('title', 'Payment Pending')
@section('content')
<div class="card" style="max-width:540px;margin:40px auto;text-align:center;padding:40px 24px;">
    <div style="width:72px;height:72px;border-radius:50%;background:rgba(251,191,36,.15);color:var(--warning);font-size:36px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <i class="fa-solid fa-clock"></i>
    </div>
    <h2 class="font-bold text-2xl mb-2">Payment Awaiting Settlement</h2>
    <p class="text-sm text-muted mb-6">Please complete the payment instructions provided by your payment provider or bank.</p>
    <div class="flex gap-3 justify-center">
        <a href="{{ route('customer.invoices.index') }}" class="btn btn-primary">Check Invoices</a>
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline">Dashboard</a>
    </div>
</div>
@endsection
