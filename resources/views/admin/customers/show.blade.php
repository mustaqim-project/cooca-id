@extends('layouts.admin')
@section('title', $customer->name)
@section('subtitle', 'Customer Profile')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers
        </a>
    </div>
    <div class="row g-4">
        {{-- LEFT: profile + tables --}}
        <div class="col-lg-8">
            {{-- Profile Card --}}
            <div class="card-saas mb-4">
                <div class="card-saas-body">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:64px;height:64px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;color:#fff;flex-shrink:0">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-0 fw-700">{{ $customer->name }}</h4>
                            <div style="color:var(--text-muted);font-size:.875rem">{{ $customer->email }}</div>
                            @if ($customer->business_name)
                                <div style="font-size:.8rem;color:var(--text-muted)"><i
                                        class="bi bi-building me-1"></i>{{ $customer->business_name }}</div>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            @if ($customer->email_verified_at)
                                <span class="badge-saas badge-saas-success">Verified</span>
                            @else
                                <span class="badge-saas badge-saas-warning">Unverified</span>
                            @endif
                            <a href="{{ route('admin.customers.edit', $customer) }}"
                                class="btn-saas btn-saas-secondary btn-saas-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="row g-3 mt-3 pt-3" style="border-top:1px solid var(--border-color)">
                        <div class="col-sm-4">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Phone</div>
                            <div style="font-size:.875rem;font-weight:500">{{ $customer->phone ?: '-' }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Registered</div>
                            <div style="font-size:.875rem;font-weight:500">{{ $customer->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Last Login</div>
                            <div style="font-size:.875rem;font-weight:500">
                                {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transactions --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-receipt me-2"></i>Transactions</h5>
                </div>
                <div class="card-saas-body p-0">
                    <div class="table-responsive">
                        <table class="table-saas">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->transactions ?? [] as $tx)
                                    <tr>
                                        <td><code style="font-size:.8rem">{{ $tx->invoice_number }}</code></td>
                                        <td>{{ $tx->product->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @php $s = $tx->status; @endphp
                                            <span
                                                class="badge-saas {{ $s === 'paid' ? 'badge-saas-success' : ($s === 'pending' ? 'badge-saas-warning' : 'badge-saas-danger') }}">
                                                {{ ucfirst($s) }}
                                            </span>
                                        </td>
                                        <td>{{ $tx->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-state-icon"><i class="bi bi-receipt"></i></div>
                                                <div class="empty-state-title">No transactions yet</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Licenses --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-key me-2"></i>Licenses</h5>
                </div>
                <div class="card-saas-body p-0">
                    <div class="table-responsive">
                        <table class="table-saas">
                            <thead>
                                <tr>
                                    <th>License Key</th>
                                    <th>Product</th>
                                    <th>Domain</th>
                                    <th>Status</th>
                                    <th>Expires</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->licenses ?? [] as $lic)
                                    <tr>
                                        <td><code style="font-size:.75rem">{{ Str::limit($lic->license_key, 20) }}</code>
                                        </td>
                                        <td>{{ $lic->product->name ?? '-' }}</td>
                                        <td>{{ $lic->domain ?: '-' }}</td>
                                        <td>
                                            <span
                                                class="badge-saas {{ $lic->status === 'active' ? 'badge-saas-success' : 'badge-saas-danger' }}">
                                                {{ ucfirst($lic->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $lic->expires_at ? $lic->expires_at->format('d M Y') : 'Lifetime' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-state-icon"><i class="bi bi-key"></i></div>
                                                <div class="empty-state-title">No licenses found</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: actions + stats --}}
        <div class="col-lg-4">
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-bar-chart me-2"></i>Summary</h5>
                </div>
                <div class="card-saas-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--primary)">
                                {{ $customer->transactions()->count() }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">Transactions</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--success)">
                                {{ $customer->licenses()->count() }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">Licenses</div>
                        </div>
                        <div class="col-12" style="border-top:1px solid var(--border-color);padding-top:.75rem">
                            <div style="font-size:1.25rem;font-weight:700">Rp
                                {{ number_format($customer->transactions()->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                            </div>
                            <div style="font-size:.75rem;color:var(--text-muted)">Total Paid</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-saas btn-saas-secondary w-100">
                        <i class="bi bi-pencil me-1"></i> Edit Customer
                    </a>
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                        class="form-confirm-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-saas btn-saas-danger w-100">
                            <i class="bi bi-trash me-1"></i> Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
