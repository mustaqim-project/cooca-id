@extends('admin.layouts.app')

@section('title', 'ERP Request Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header & Action Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.erp-requests.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold">ERP Request #{{ $request->id ?? '001' }}</h2>
                    <p class="text-secondary mb-0">Manage customer ERP setup workflow and status transitions.</p>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <!-- Dynamic Actions based on ErpRequest Status -->
                <form action="{{ route('admin.erp-requests.mark-waiting-setup', $request->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-hourglass-split me-2"></i> Mark Waiting Setup
                    </button>
                </form>
                <form action="{{ route('admin.erp-requests.mark-in-setup', $request->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info rounded-pill px-4 hover-lift shadow-sm text-white">
                        <i class="bi bi-gear-wide-connected me-2"></i> Mark In Setup
                    </button>
                </form>
                <form action="{{ route('admin.erp-requests.mark-domain-setup', $request->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-globe me-2"></i> Mark Domain Setup
                    </button>
                </form>
                <form action="{{ route('admin.erp-requests.mark-testing', $request->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-bug me-2"></i> Mark Testing
                    </button>
                </form>
                <button type="button" class="btn btn-success rounded-pill px-4 hover-lift shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#confirmReadyModal">
                    <i class="bi bi-check-circle me-2"></i> Confirm Ready & Activate Trial
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-4">
            <!-- Sidebar Customer Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $request->customer->name ?? 'John Doe' }}</h5>
                            <p class="text-secondary fs-7 mb-0">{{ $request->customer->email ?? 'customer@example.com' }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3 fs-7">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Status</span>
                            <span
                                class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1">{{ $request->status ?? 'Pending Approval' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Product Requested</span>
                            <span class="fw-medium">{{ $request->product->name ?? 'Odoo Enterprise' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Affiliator Partner</span>
                            <span class="fw-medium">{{ $request->affiliator->name ?? 'Direct' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Approved By</span>
                            <span class="fw-medium">{{ $request->approvedBy->name ?? 'Pending' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Submitted At</span>
                            <span
                                class="fw-medium">{{ $request->created_at ?? null ? $request->created_at->format('M d, Y H:i') : 'Oct 15, 2026' }}</span>
                        </div>
                    </div>

                    <hr class="border-light my-4">

                    <!-- Approve / Reject Actions -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-success rounded-pill flex-grow-1" data-bs-toggle="modal"
                            data-bs-target="#approveModal">
                            <i class="bi bi-check-lg me-1"></i> Approve
                        </button>
                        <button class="btn btn-outline-danger rounded-pill flex-grow-1" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="bi bi-x-lg me-1"></i> Reject
                        </button>
                    </div>
                </div>
            </div>

            <!-- Details & Setup Info -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Workflow & Setup Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="text-secondary fs-7 mb-1 d-block">Admin Notes / Rejection Reason</label>
                                <div class="p-3 bg-light rounded-3 border">
                                    {{ $request->admin_notes ?? 'No specific admin notes or rejection notes attached to this ERP request yet.' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <h6 class="fw-bold mt-2 mb-3"><i class="bi bi-globe me-2 text-primary"></i> Requested
                                    Domains</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless align-middle mb-0">
                                        <thead class="bg-light text-secondary text-uppercase fs-7">
                                            <tr>
                                                <th class="py-2 px-3">Domain Name</th>
                                                <th class="py-2 px-3">Type</th>
                                                <th class="py-2 px-3 text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="py-2 px-3 fw-medium">customer-app.cooca.id</td>
                                                <td class="py-2 px-3 text-secondary">Subdomain</td>
                                                <td class="py-2 px-3 text-end"><span
                                                        class="badge bg-success-subtle text-success border rounded-pill">Active</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Approve Request -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header border-bottom p-4 bg-light">
                    <h5 class="modal-title fw-bold">Approve ERP Request</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.erp-requests.approve', $request->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-floating">
                            <textarea class="form-control rounded-3 shadow-none border bg-transparent" id="admin_notes" name="admin_notes"
                                placeholder="Notes" style="height: 100px"></textarea>
                            <label for="admin_notes">Optional Admin Notes</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-3 bg-light d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm hover-lift"><i
                                class="bi bi-check-lg me-2"></i> Confirm Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Reject Request -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header border-bottom p-4 bg-light">
                    <h5 class="modal-title fw-bold text-danger">Reject ERP Request</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.erp-requests.reject', $request->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-floating">
                            <textarea class="form-control rounded-3 shadow-none border bg-transparent" id="rejection_reason"
                                name="rejection_reason" placeholder="Reason" style="height: 100px" required></textarea>
                            <label for="rejection_reason">Rejection Reason (Required)</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-3 bg-light d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm hover-lift"><i
                                class="bi bi-x-lg me-2"></i> Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Confirm Ready & Activate Trial -->
    <div class="modal fade" id="confirmReadyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header border-bottom p-4 bg-light">
                    <h5 class="modal-title fw-bold text-success">Activate Trial & Confirm Ready</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.erp-requests.confirm-ready', $request->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control rounded-3 shadow-none border bg-transparent"
                                id="trial_days" name="trial_days" value="14" min="1" max="365"
                                required>
                            <label for="trial_days">Trial Duration (Days)</label>
                        </div>
                        <p class="text-secondary fs-7 mb-0">This action will trigger the
                            <code>TrialActivationService</code>, create a license, and dispatch email notifications to the
                            customer.
                        </p>
                    </div>
                    <div class="modal-footer border-top p-3 bg-light d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm hover-lift"><i
                                class="bi bi-rocket-takeoff me-2"></i> Activate Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
