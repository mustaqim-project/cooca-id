@extends('layouts.admin')
@section('title', $affiliator->name)
@section('subtitle', 'Affiliator Profile')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.affiliators.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Affiliators
        </a>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Profile --}}
            <div class="card-saas mb-4">
                <div class="card-saas-body">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:64px;height:64px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;color:#fff;flex-shrink:0">
                            {{ strtoupper(substr($affiliator->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-0 fw-700">{{ $affiliator->name }}</h4>
                            <div style="color:var(--text-muted);font-size:.875rem">{{ $affiliator->email }}</div>
                            <div style="font-size:.8rem;color:var(--text-muted)">
                                Referral: <code>{{ $affiliator->referral_code }}</code>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2 align-items-end">
                            @if ($affiliator->is_active)
                                <span class="badge-saas badge-saas-success">Active</span>
                            @else
                                <span class="badge-saas badge-saas-danger">Inactive</span>
                            @endif
                            <a href="{{ route('admin.affiliators.edit', $affiliator) }}"
                                class="btn-saas btn-saas-secondary btn-saas-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="row g-3 mt-3 pt-3" style="border-top:1px solid var(--border-color)">
                        <div class="col-sm-4 text-center">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--primary)">
                                {{ $affiliator->downlines->count() }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">Downlines</div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--success)">Rp
                                {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">Total Commission</div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--warning)">Rp
                                {{ number_format($affiliator->withdrawn ?? 0, 0, ',', '.') }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">Withdrawn</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Downlines --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-people me-2"></i>Downlines</h5>
                </div>
                <div class="card-saas-body p-0">
                    <div class="table-responsive">
                        <table class="table-saas">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Total Spend</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($downlines as $dl)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dl->name }}</td>
                                        <td style="color:var(--text-muted)">{{ $dl->email }}</td>
                                        <td>{{ $dl->created_at->format('d M Y') }}</td>
                                        <td>Rp
                                            {{ number_format($dl->transactions()->where('status', 'paid')->sum('amount') ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                                <div class="empty-state-title">No downlines yet</div>
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

        <div class="col-lg-4">
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-info-circle me-2"></i>Account Details</h5>
                </div>
                <div class="card-saas-body">
                    <dl style="font-size:.875rem;margin:0">
                        <dt style="color:var(--text-muted);font-weight:500">Joined</dt>
                        <dd class="mb-2">{{ $affiliator->created_at->format('d M Y H:i') }}</dd>
                        <dt style="color:var(--text-muted);font-weight:500">Last Login</dt>
                        <dd class="mb-2">
                            {{ $affiliator->last_login_at ? $affiliator->last_login_at->diffForHumans() : 'Never' }}</dd>
                        <dt style="color:var(--text-muted);font-weight:500">Commission Rate</dt>
                        <dd class="mb-0">{{ $affiliator->commission_rate ?? config('affiliate.commission_rate', 10) }}%
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.affiliators.edit', $affiliator) }}" class="btn-saas btn-saas-secondary w-100">
                        <i class="bi bi-pencil me-1"></i> Edit Affiliator
                    </a>
                    <form action="{{ route('admin.affiliators.destroy', $affiliator) }}" method="POST"
                        class="form-confirm-delete">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-saas btn-saas-danger w-100">
                            <i class="bi bi-trash me-1"></i> Delete Affiliator
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
