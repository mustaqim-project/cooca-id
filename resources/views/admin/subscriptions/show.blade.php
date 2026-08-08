@extends('layouts.admin')

@section('title', 'Subscription Details — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Subscription Detail #SUB-{{ $subscription->id ?? 1 }}</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <div class="font-bold text-xl text-primary mb-2">{{ $subscription->product->name ?? 'Software Product' }}</div>
        <div class="text-sm text-muted">Plan: {{ $subscription->subscriptionPlan->name ?? 'Standard' }}</div>
        <div class="text-sm font-semibold my-2">Status: <span class="badge badge-success">{{ strtoupper($subscription->status ?? 'ACTIVE') }}</span></div>
    </div>
</div>
@endsection
