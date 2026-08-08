@extends('layouts.admin')

@section('title', 'Customer Detail — ' . ($customer->name ?? 'Account') . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.customers.index') }}">Customers</a>
            <span>/</span>
            <span>{{ $customer->name }}</span>
        </div>
        <h1 class="page-title">{{ $customer->name }}</h1>
        <p class="page-subtitle">{{ $customer->business_name ?? 'Individual Business' }} — {{ $customer->email }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-outline">✏️ Edit Profile</a>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-ghost">← Back to List</a>
    </div>
</div>

<div class="grid-31">
    <div class="flex-col gap-5">
        {{-- Subscriptions Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Active Subscriptions</div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->subscriptions ?? [] as $sub)
                                <tr>
                                    <td class="font-bold">{{ $sub->product->name ?? 'Software' }}</td>
                                    <td>{{ $sub->subscriptionPlan->name ?? 'Standard' }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ strtoupper($sub->status ?? 'ACTIVE') }}</span>
                                    </td>
                                    <td class="text-xs text-muted">{{ optional($sub->ends_at)->format('d M Y') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 24px;">No active subscriptions for this customer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Licenses Card --}}
        <div class="card mt-4">
            <div class="card-header">
                <div class="card-title">Issued Software Licenses</div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>License Key</th>
                                <th>Product</th>
                                <th>Domain</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->licenses ?? [] as $lic)
                                <tr>
                                    <td><code class="text-primary font-bold">{{ $lic->license_key }}</code></td>
                                    <td>{{ $lic->product->name ?? 'SaaS Module' }}</td>
                                    <td>{{ $lic->domain ?? 'Unbound' }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ strtoupper($lic->status ?? 'ACTIVE') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 24px;">No licenses generated for this customer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Profile Overview Sidebar --}}
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Account Overview</div>
            </div>
            <div class="card-body">
                <div class="flex items-center gap-3 mb-4">
                    @if($customer->logo_path)
                        <img src="{{ $customer->logo_url }}" alt="Logo" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                    @else
                        <div class="avatar avatar-lg">
                            {{ strtoupper(substr($customer->name ?? 'C', 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-base">{{ $customer->name }}</div>
                        <div class="text-xs text-muted">{{ $customer->email }}</div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="flex-col gap-3">
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Business Name</div>
                        <div class="font-semibold text-sm">{{ $customer->business_name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Company Domain</div>
                        <div class="font-semibold text-sm">
                            @if($customer->domain)
                                <a href="http://{{ $customer->domain }}" target="_blank" class="text-primary" style="text-decoration: underline;">{{ $customer->domain }} <i class="fa-solid fa-external-link" style="font-size: 10px;"></i></a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Phone Number</div>
                        <div class="font-semibold text-sm">{{ $customer->phone ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Affiliate Partner</div>
                        <div class="font-semibold text-sm">
                            @if($customer->affiliator)
                                <a href="{{ route('admin.affiliators.show', $customer->affiliator->id) }}" class="text-primary font-bold" style="text-decoration: underline;">{{ $customer->affiliator->name }} ({{ $customer->affiliator->referral_code }})</a>
                            @else
                                <span class="text-muted">Direct Client (None)</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Joined Date</div>
                        <div class="font-semibold text-sm">{{ optional($customer->created_at)->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
