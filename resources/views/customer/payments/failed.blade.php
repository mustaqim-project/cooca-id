@extends('layouts.customer')
@section('title', 'Payment Failed')
@section('content')
<div class="card" style="max-width:540px;margin:40px auto;text-align:center;padding:40px 24px;">
    <div style="width:72px;height:72px;border-radius:50%;background:rgba(var(--danger-rgb),.15);color:var(--danger);font-size:36px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <h2 class="font-bold text-2xl mb-2">Payment Failed or Cancelled</h2>
    <p class="text-sm text-muted mb-6">Your payment could not be processed. Please try again or choose a different payment method.</p>
    <div class="flex gap-3 justify-center">
        <a href="{{ route('customer.invoices.index') }}" class="btn btn-primary">Try Again</a>
        <a href="{{ route('customer.tickets.create') }}" class="btn btn-outline">Contact Support</a>
    </div>
</div>
@endsection
