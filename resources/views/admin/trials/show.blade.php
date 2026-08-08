@extends('layouts.admin')

@section('title', 'Trial Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.trials.index') }}">Trials</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Trial Application #{{ $trial->id ?? 1 }}</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <div class="font-bold text-xl text-primary mb-2">{{ $trial->business_name ?? 'Client Business' }}</div>
        <div class="text-sm">Subdomain: <code>{{ $trial->subdomain ?? 'demo' }}.cooca.id</code></div>
        <div class="text-xs text-muted my-2">Status: <span class="badge badge-warning">{{ strtoupper($trial->status ?? 'PENDING') }}</span></div>
        @if(($trial->status ?? '') === 'pending')
            <form action="{{ route('admin.trials.approve', $trial->id ?? 1) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-success w-full">🧪 Approve & Deploy Trial Instance</button>
            </form>
        @endif
    </div>
</div>
@endsection
