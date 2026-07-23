@extends('affiliator.layouts.app')

@section('title', 'My Referrals')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">My Referrals</h2>
                <p class="text-secondary mb-0">Track and manage your registered referrals.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('affiliator.referrals.stats') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-bar-chart me-1"></i> View Statistics
                </a>
            </div>
        </div>

        <!-- Referrals Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search referrals...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Customer</th>
                            <th class="py-3 px-3 border-0">Registration Date</th>
                            <th class="py-3 px-4 border-0 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($referrals as $referral)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($referral->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $referral->name }}</div>
                                            <div class="text-secondary fs-7">
                                                {{ substr($referral->email, 0, 3) }}***@{{ explode('@', $referral->email)[1] ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ \Carbon\Carbon::parse($referral->created_at)->format('F d, Y') }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        Registered
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-people fs-1"></i></div>
                                    <h6 class="fw-medium">No referrals yet</h6>
                                    <p class="fs-7 mb-0">Share your referral link to start earning commissions!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($referrals, 'hasPages') && $referrals->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $referrals->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
