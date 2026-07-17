@extends('admin.layouts.app')

@section('title', 'Licenses')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Licenses</h2>
                <p class="text-secondary mb-0">Manage product license keys and activation records.</p>
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
                        placeholder="Search license codes...">
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
                            <th class="py-3 px-4 border-0">License Code</th>
                            <th class="py-3 px-3 border-0">Customer</th>
                            <th class="py-3 px-3 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Domain</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Expires</th>
                            <th class="py-3 px-3 border-0">Created</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($licenses as $license)
                            <tr>
                                <td class="py-3 px-4">
                                    <code class="text-primary fw-bold">{{ $license->license_code }}</code>
                                    @if ($license->is_trial)
                                        <span
                                            class="badge bg-info-subtle text-info border border-info-subtle rounded-pill ms-1">Trial</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium">{{ $license->customer->name ?? 'N/A' }}</div>
                                    <div class="text-secondary fs-7">{{ $license->customer->email ?? '' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-dark border">{{ $license->product->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($license->domain)
                                        <code class="text-secondary fs-7">{{ $license->domain }}</code>
                                    @else
                                        <span class="text-muted fs-7">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @switch($license->status)
                                        @case('active')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active</span>
                                        @break

                                        @case('expired')
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Expired</span>
                                        @break

                                        @case('revoked')
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">Revoked</span>
                                        @break

                                        @default
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Inactive</span>
                                    @endswitch
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $license->expires_at ? $license->expires_at->format('d M Y') : 'Lifetime' }}
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">{{ $license->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li>
                                                <a class="dropdown-item py-2"
                                                    href="{{ route('admin.licenses.show', $license->id) }}">
                                                    <i class="bi bi-eye me-2 text-primary"></i> View Details
                                                </a>
                                            </li>
                                            @if ($license->status === 'active')
                                                <li>
                                                    <button type="button" class="dropdown-item py-2 text-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#revokeModal-{{ $license->id }}">
                                                        <i class="bi bi-slash-circle me-2"></i> Revoke License
                                                    </button>
                                                </li>
                                            @elseif($license->status !== 'revoked')
                                                <li>
                                                    <form action="{{ route('admin.licenses.activate', $license->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-success">
                                                            <i class="bi bi-check-circle me-2"></i> Activate License
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    @if ($license->status === 'active')
                                        <!-- Revoke Modal -->
                                        <div class="modal fade" id="revokeModal-{{ $license->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 rounded-4 shadow">
                                                    <form action="{{ route('admin.licenses.revoke', $license->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Revoke License</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-secondary mb-3">Revoking license
                                                                <code>{{ $license->license_code }}</code> for
                                                                <strong>{{ $license->customer->name ?? 'N/A' }}</strong>.
                                                            </p>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium">Category <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="category" class="form-select rounded-3"
                                                                    required>
                                                                    <option value="">Select reason category</option>
                                                                    <option value="violation">Terms Violation</option>
                                                                    <option value="fraud">Fraud / Abuse</option>
                                                                    <option value="non_payment">Non-Payment</option>
                                                                    <option value="customer_request">Customer Request
                                                                    </option>
                                                                    <option value="other">Other</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium">Reason
                                                                    (optional)</label>
                                                                <textarea name="reason" class="form-control rounded-3" rows="3" maxlength="500"
                                                                    placeholder="Describe the reason..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-danger rounded-pill px-4">Revoke
                                                                License</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-5 text-center text-secondary">
                                        <div class="mb-3"><i class="bi bi-key fs-1"></i></div>
                                        <h6 class="fw-medium">No Licenses Found</h6>
                                        <p class="fs-7">No product licenses have been generated yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (isset($licenses) && $licenses->hasPages())
                    <div class="card-footer bg-transparent border-top border-light p-4">
                        {{ $licenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endsection
