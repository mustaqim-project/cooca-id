@extends('layouts.admin')

@section('title', 'License Key Details — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.licenses.index') }}">Licenses</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">License Details</h1>
    </div>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <code class="text-2xl font-bold text-primary">{{ $license->license_key ?? 'LIC-XXXX' }}</code>
            <span class="badge badge-success">{{ strtoupper($license->status ?? 'ACTIVE') }}</span>
        </div>
        <div class="grid-2 gap-4 my-4">
            <div>
                <div class="text-xs text-muted font-bold uppercase">Customer</div>
                <div class="font-semibold text-sm">{{ $license->customer->name ?? 'N/A' }} ({{ $license->customer->email ?? '' }})</div>
            </div>
            <div>
                <div class="text-xs text-muted font-bold uppercase">Bound Domain</div>
                <div class="font-semibold text-sm">🌐 {{ $license->domain ?? 'Unbound' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
