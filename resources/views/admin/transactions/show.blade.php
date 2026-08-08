@extends('layouts.admin')

@section('title', 'Transaction Receipt — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.transactions.index') }}">Transactions</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Transaction Invoice #{{ $transaction->reference ?? $transaction->id }}</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <span class="badge badge-success font-bold text-base">PAID</span>
            <div class="font-bold text-2xl text-primary">Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="text-sm my-2">Customer: <strong>{{ $transaction->customer->name ?? 'Client' }}</strong></div>
        <div class="text-xs text-muted">Gateway: {{ strtoupper($transaction->payment_gateway ?? 'Midtrans') }}</div>
    </div>
</div>
@endsection
