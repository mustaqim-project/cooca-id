@extends('admin.layouts.app')

@section('title', 'Vouchers')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Vouchers</h2>
                <p class="text-secondary mb-0">Manage discount codes and promotions.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create Voucher
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search vouchers...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Code</th>
                            <th class="py-3 px-3 border-0">Type</th>
                            <th class="py-3 px-3 border-0">Value</th>
                            <th class="py-3 px-3 border-0">Usage</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Valid Until</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td class="py-3 px-4">
                                    <span
                                        class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">{{ $voucher->code }}</span>
                                </td>
                                <td class="py-3 px-3 text-capitalize">{{ $voucher->type ?? 'percentage' }}</td>
                                <td class="py-3 px-3 fw-medium">
                                    @if (($voucher->type ?? '') == 'fixed')
                                        Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                    @else
                                        {{ $voucher->value }}%
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $voucher->used_count ?? 0 }} / {{ $voucher->max_uses ?? '∞' }}
                                </td>
                                <td class="py-3 px-3">
                                    @if ($voucher->is_active ?? true)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $voucher->expires_at ? \Carbon\Carbon::parse($voucher->expires_at)->format('d M Y') : 'No Expiry' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.vouchers.show', $voucher->id) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.vouchers.edit', $voucher->id) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this voucher?');">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-ticket-perforated fs-1"></i></div>
                                    <h6 class="fw-medium">No Vouchers Found</h6>
                                    <p class="fs-7">Get started by creating your first promotional voucher code.</p>
                                    <a href="{{ route('admin.vouchers.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3 mt-2">Create Voucher</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($vouchers) && $vouchers->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
