@extends('admin.layouts.app')

@section('title', 'ERP Requests')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">ERP Setup Requests</h2>
                <p class="text-secondary mb-0">Review, approve, and manage customer ERP setup progress.</p>
            </div>
            <div class="d-flex gap-2">
                <!-- ERP Requests are created by customers, so no 'Create' button here -->
            </div>
        </div>

        <!-- Stats Table / Filter Toolbar -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search requests...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-secondary fs-7">Status:</span>
                    <select class="form-select form-select-sm rounded-pill border-light bg-light text-secondary shadow-none"
                        style="width: 140px;">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_setup">In Setup</option>
                        <option value="ready">Ready</option>
                    </select>
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Filter"><i
                            class="bi bi-funnel"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Client</th>
                            <th class="py-3 px-3 border-0">Product / Package</th>
                            <th class="py-3 px-3 border-0">Affiliator</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Created At</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($requests as $req)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center fw-bold text-uppercase"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($req->customer->name ?? 'U', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $req->customer->name ?? 'Unknown Customer' }}</div>
                                            <div class="text-secondary fs-7">{{ $req->customer->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="fw-medium">{{ $req->product->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $req->affiliator->name ?? 'Direct (No Affiliator)' }}
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'waiting_setup' => 'secondary',
                                            'in_setup' => 'primary',
                                            'domain_setup' => 'dark',
                                            'testing' => 'info',
                                            'ready' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                        $color = $statusColors[$req->status] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-3 py-1 text-capitalize">
                                        {{ str_replace('_', ' ', $req->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $req->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('admin.erp-requests.show', $req->id) }}"
                                        class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                                        Manage <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No ERP Requests Found</h6>
                                    <p class="fs-7">There are currently no ERP setup requests.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($requests->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
