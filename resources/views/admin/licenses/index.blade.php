@extends('layouts.admin')

@section('title', 'Software Licenses — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Licenses</span>
        </div>
        <h1 class="page-title">License Management</h1>
        <p class="page-subtitle">Monitor software activation keys, domain binding, revocation history, and customer appeals.</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="alert('Use customer detail or subscription to generate keys.');">
            <span>🔑</span> Manual License Key
        </button>
    </div>
</div>

<div class="filter-bar">
    <div class="filter-search">
        <span class="filter-search-icon">🔍</span>
        <input type="text" placeholder="Search license key, domain, or customer email...">
    </div>
    <select class="form-select" style="width: 160px;">
        <option value="">All Statuses</option>
        <option value="active">Active</option>
        <option value="expired">Expired</option>
        <option value="revoked">Revoked</option>
        <option value="suspended">Suspended</option>
    </select>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>License Key</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Bound Domain</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licenses as $lic)
                        @php
                            $licenseObj = is_array($lic) ? (object)$lic : $lic;
                        @endphp
                        <tr>
                            <td>
                                <code class="text-primary font-bold" style="font-size: 13px;">{{ $licenseObj->license_key ?? $licenseObj->key ?? 'LIC-XXXX' }}</code>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $licenseObj->customer->name ?? $licenseObj->customer_name ?? 'Client' }}</div>
                                <div class="text-xs text-muted">{{ $licenseObj->customer->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-purple">{{ $licenseObj->product->name ?? $licenseObj->product_name ?? 'Module' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-accent">🌐 {{ $licenseObj->domain ?? 'Unbound' }}</span>
                            </td>
                            <td>
                                @if(($licenseObj->status ?? 'active') === 'active')
                                    <span class="badge badge-success"><span class="status-dot active"></span> ACTIVE</span>
                                @elseif(($licenseObj->status ?? '') === 'revoked')
                                    <span class="badge badge-danger"><span class="status-dot danger"></span> REVOKED</span>
                                @else
                                    <span class="badge badge-warning"><span class="status-dot pending"></span> {{ strtoupper($licenseObj->status ?? 'EXPIRED') }}</span>
                                @endif
                            </td>
                            <td class="text-xs text-muted">
                                {{ isset($licenseObj->expires_at) ? \Carbon\Carbon::parse($licenseObj->expires_at)->format('d M Y') : 'Lifetime' }}
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.licenses.show', $licenseObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ Detail</a>
                                    @if(($licenseObj->status ?? 'active') === 'active')
                                        <form action="{{ route('admin.licenses.revoke', $licenseObj->id ?? 1) }}" method="POST" onsubmit="return confirm('Revoke this license?');" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="category" value="administrative">
                                            <button type="submit" class="btn btn-ghost btn-sm text-danger">🚫 Revoke</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                                No licenses generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
