@extends('layouts.admin')

@section('title', 'API & Service Integrations — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>API Integrations</span>
        </div>
        <h1 class="page-title">Third-Party Services & Integrations</h1>
        <p class="page-subtitle">Configure credentials and webhooks for Midtrans, Xendit, Tripay, WhatsApp Gateway, and Mailgun.</p>
    </div>
</div>

<div class="grid-3">
    @foreach($integrations as $integ)
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="avatar avatar-md" style="background: var(--primary-soft); color: var(--primary);">
                        ⚡
                    </div>
                    <div>
                        <div class="card-title">{{ $integ->name ?? strtoupper($integ->provider) }}</div>
                        <div class="text-xs text-muted">{{ strtoupper($integ->provider) }} Gateway</div>
                    </div>
                </div>
                <div>
                    @if($integ->is_active)
                        <span class="badge badge-success">CONNECTED</span>
                    @else
                        <span class="badge badge-muted">OFF</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <p class="text-xs text-muted mb-4">API configuration, Secret Keys, Server Keys, and Webhook URL mapping.</p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.api-integrations.edit', $integ->provider) }}" class="btn btn-outline btn-sm w-full">⚙️ Configure Keys</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
