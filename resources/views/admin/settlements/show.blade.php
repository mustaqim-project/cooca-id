@extends('layouts.admin')

@section('title', 'Settlement Details — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.settlements.index') }}">Settlements</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Payout Request Detail</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <div class="font-bold text-2xl text-success mb-2">Rp {{ number_format($settlement->amount ?? 0, 0, ',', '.') }}</div>
        <div class="text-sm text-muted">Bank: {{ $settlement->bank_name ?? '' }} — {{ $settlement->account_number ?? '' }} (a.n {{ $settlement->account_holder ?? '' }})</div>
    </div>
</div>
@endsection
