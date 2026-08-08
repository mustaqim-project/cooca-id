@extends('layouts.customer')
@section('title', 'Domain Management')
@section('breadcrumb')
    <span class="crumb-current">Domains</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-globe" style="color:var(--primary);margin-right:10px;"></i>Domain Management</h1>
        <p class="page-subtitle">Configure custom domains and subdomains for your COOCA.ID instances.</p>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <div class="card-title">Assigned Subdomains & Custom Domains</div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Domain / Subdomain</th>
                        <th>Product / Instance</th>
                        <th>DNS Status</th>
                        <th>SSL Certificate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($domains ?? $tenants ?? [] as $domain)
                    <tr>
                        <td class="font-bold">
                            <a href="https://{{ $domain->domain ?? $domain->id . '.cooca.id' }}" target="_blank" class="text-primary">
                                {{ $domain->domain ?? $domain->id . '.cooca.id' }}
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                            </a>
                        </td>
                        <td class="text-sm">{{ $domain->product?->name ?? 'COOCA Instance' }}</td>
                        <td>
                            @if(($domain->is_verified ?? true))
                                <span class="badge badge-success"><i class="fa-solid fa-check"></i> Verified</span>
                            @else
                                <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Pending DNS</span>
                            @endif
                        </td>
                        <td><span class="badge badge-success"><i class="fa-solid fa-lock"></i> Active (Auto SSL)</span></td>
                        <td>
                            <form method="POST" action="{{ route('customer.domains.verify', $domain->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm">Verify DNS</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">🌐</div>
                                <div class="empty-state-title">No Custom Domains</div>
                                <div class="empty-state-text">Custom domains will be created automatically when your license is activated.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">📌 DNS Setup Guide for Custom Domains</div>
    </div>
    <div class="card-body" style="line-height:1.6;font-size:13.5px;">
        <p class="mb-3">To point your own domain (e.g., <code>erp.yourcompany.com</code>) to your COOCA.ID instance, add the following CNAME record in your domain registrar DNS settings:</p>
        <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:16px;" class="font-mono text-xs mb-3">
            <div><strong>Type:</strong> CNAME</div>
            <div><strong>Host / Name:</strong> erp (or @ for root domain)</div>
            <div><strong>Value / Target:</strong> app.cooca.id</div>
            <div><strong>TTL:</strong> Automatic / 3600</div>
        </div>
        <p class="text-xs text-muted">DNS propagation may take up to 24 hours. Free SSL certificates are automatically issued once CNAME verification passes.</p>
    </div>
</div>
@endsection
