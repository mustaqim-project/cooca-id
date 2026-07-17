@extends('admin.layouts.app')

@section('title', 'Affiliator Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.affiliators.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Affiliator Details</h2>
                    <p class="text-secondary mb-0">View full information and activity.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                @if ($affiliator->status === 'active')
                    <form action="{{ route('admin.affiliators.suspend', $affiliator->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning rounded-pill px-4 hover-lift shadow-sm"
                            onclick="return confirm('Suspend this affiliator?');">
                            <i class="bi bi-pause-circle me-2"></i> Suspend
                        </button>
                    </form>
                @elseif ($affiliator->status === 'suspended')
                    <form action="{{ route('admin.affiliators.reactivate', $affiliator->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 hover-lift shadow-sm"
                            onclick="return confirm('Reactivate this affiliator?');">
                            <i class="bi bi-play-circle me-2"></i> Reactivate
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.affiliators.edit', $affiliator->id) }}"
                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>
                <form action="{{ route('admin.affiliators.destroy', $affiliator->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm"
                        onclick="return confirm('Are you sure you want to delete this affiliator?');">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm fw-bold fs-1"
                        style="width: 80px; height: 80px;">
                        {{ strtoupper(substr($affiliator->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $affiliator->name }}</h4>
                    <p class="text-secondary mb-3">{{ $affiliator->email }}</p>
                    <div>
                        @if ($affiliator->status === 'active')
                            <span
                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Active</span>
                        @elseif ($affiliator->status === 'suspended')
                            <span
                                class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle">Suspended</span>
                        @else
                            <span
                                class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 border border-danger-subtle">{{ ucfirst($affiliator->status ?? 'inactive') }}</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Referral Code</span>
                        <span class="fw-medium fs-7"><code
                                class="text-primary">{{ $affiliator->referral_code }}</code></span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Balance</span>
                        <span class="fw-bold text-success fs-7">Rp
                            {{ number_format($affiliator->balance, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Bank</span>
                        <span class="fw-medium fs-7">{{ $affiliator->bank_name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Bank Account</span>
                        <span class="fw-medium fs-7">{{ $affiliator->bank_account ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Parent</span>
                        <span class="fw-medium fs-7">
                            @if ($affiliator->parent)
                                <a
                                    href="{{ route('admin.affiliators.show', $affiliator->parent->id) }}">{{ $affiliator->parent->name }}</a>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span class="fw-medium fs-7">{{ $affiliator->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span class="fw-medium fs-7">{{ $affiliator->updated_at->format('d M Y, H:i') }}</span>
                    </div>

                    @if ($affiliator->status === 'suspended')
                        <hr class="border-light my-4">
                        <div class="text-start">
                            <h6 class="fw-bold text-warning mb-2"><i class="bi bi-exclamation-triangle me-1"></i> Suspension
                                Details</h6>
                            <div class="mb-2">
                                <span class="text-secondary fs-7">Reason Type:</span>
                                <span
                                    class="fw-medium fs-7">{{ ucfirst(str_replace('_', ' ', $affiliator->suspension_reason_type ?? '-')) }}</span>
                            </div>
                            <div>
                                <span class="text-secondary fs-7">Notes:</span>
                                <p class="fw-medium fs-7 mb-0">{{ $affiliator->suspension_reason_notes ?? '-' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="d-flex flex-column gap-4">

                    <!-- Stats Cards -->
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="card border-0 shadow-sm rounded-4 glass p-3 text-center">
                                <div class="text-secondary fs-7 mb-1">Referrals</div>
                                <div class="fw-bold fs-4">{{ $affiliator->customers()->count() }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card border-0 shadow-sm rounded-4 glass p-3 text-center">
                                <div class="text-secondary fs-7 mb-1">Downlines</div>
                                <div class="fw-bold fs-4">{{ $downlines->count() }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card border-0 shadow-sm rounded-4 glass p-3 text-center">
                                <div class="text-secondary fs-7 mb-1">Total Commission</div>
                                <div class="fw-bold fs-4">Rp
                                    {{ number_format($affiliator->commissions()->where('status', 'cleared')->sum('commission_amount'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commission History -->
                    <div class="card border-0 shadow-sm rounded-4 glass">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-cash-stack me-2"></i> Commission History</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary fs-7 text-uppercase">
                                    <tr>
                                        <th class="border-0 py-3 px-3">Plan</th>
                                        <th class="border-0 py-3 px-3">Level</th>
                                        <th class="border-0 py-3 px-3">Gross</th>
                                        <th class="border-0 py-3 px-3">Rate</th>
                                        <th class="border-0 py-3 px-3">Commission</th>
                                        <th class="border-0 py-3 px-3">Status</th>
                                        <th class="border-0 py-3 px-3">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($affiliator->commissions()->latest()->take(10)->get() as $commission)
                                        <tr>
                                            <td class="py-3 px-3 fw-medium">{{ $commission->plan_name ?? '-' }}</td>
                                            <td class="py-3 px-3">
                                                <span
                                                    class="badge bg-info-subtle text-info rounded-pill px-2">L{{ $commission->level }}</span>
                                            </td>
                                            <td class="py-3 px-3 text-secondary fs-7">{{ $commission->formatted_gross }}
                                            </td>
                                            <td class="py-3 px-3 text-secondary fs-7">{{ $commission->percentage }}</td>
                                            <td class="py-3 px-3 fw-bold">{{ $commission->formatted_amount }}</td>
                                            <td class="py-3 px-3">
                                                @switch($commission->status)
                                                    @case('pending')
                                                        <span
                                                            class="badge bg-warning-subtle text-warning rounded-pill px-2">Pending</span>
                                                    @break

                                                    @case('cleared')
                                                        <span
                                                            class="badge bg-success-subtle text-success rounded-pill px-2">Cleared</span>
                                                    @break

                                                    @case('cancelled')
                                                        <span
                                                            class="badge bg-danger-subtle text-danger rounded-pill px-2">Cancelled</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td class="py-3 px-3 text-secondary fs-7">
                                                {{ $commission->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-secondary">No commission
                                                    records yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Downlines -->
                        <div class="card border-0 shadow-sm rounded-4 glass">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2"></i> Downlines</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-secondary fs-7 text-uppercase">
                                        <tr>
                                            <th class="border-0 py-3 px-3">Name</th>
                                            <th class="border-0 py-3 px-3">Email</th>
                                            <th class="border-0 py-3 px-3">Referral Code</th>
                                            <th class="border-0 py-3 px-3">Status</th>
                                            <th class="border-0 py-3 px-3">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        @forelse($downlines as $downline)
                                            <tr>
                                                <td class="py-3 px-3">
                                                    <a href="{{ route('admin.affiliators.show', $downline->id) }}"
                                                        class="fw-medium text-decoration-none">{{ $downline->name }}</a>
                                                </td>
                                                <td class="py-3 px-3 text-secondary fs-7">{{ $downline->email }}</td>
                                                <td class="py-3 px-3"><code
                                                        class="text-primary">{{ $downline->referral_code }}</code></td>
                                                <td class="py-3 px-3">
                                                    @if ($downline->status === 'active')
                                                        <span
                                                            class="badge bg-success-subtle text-success rounded-pill px-2">Active</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger-subtle text-danger rounded-pill px-2">{{ ucfirst($downline->status ?? 'inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3 text-secondary fs-7">
                                                    {{ $downline->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-secondary">No downlines yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection
