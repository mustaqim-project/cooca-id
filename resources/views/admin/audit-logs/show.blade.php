@extends('admin.layouts.app')

@section('title', 'Audit Log Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-primary font-monospace">AL-9021</h2>
                    <p class="text-secondary mb-0">Full payload and request trace inspection.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary"
                    onclick="window.print()">
                    <i class="bi bi-printer me-2"></i> Print Log
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Event Overview</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fs-7">Action</span>
                        <span
                            class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 text-uppercase">UPDATED</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fs-7">Module</span>
                        <span class="fw-bold">Settings / Tax Configuration</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fs-7">Severity</span>
                        <span
                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">High</span>
                    </div>

                    <hr class="border-light my-3">

                    <div class="mb-3">
                        <label class="text-secondary fs-7 mb-1 d-block">Timestamp</label>
                        <div class="fw-medium">Jul 15, 2026, 16:05:12 (UTC+7)</div>
                    </div>
                    <div>
                        <label class="text-secondary fs-7 mb-1 d-block">Description</label>
                        <div class="fw-medium text-dark">Changed global tax rate from 10% to 11%</div>
                    </div>
                </div>

                <!-- Actor Info -->
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Actor / User</h5>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="https://ui-avatars.com/api/?name=Admin+Super&background=random"
                            class="rounded-circle shadow-sm" width="48" height="48" alt="Actor">
                        <div>
                            <div class="fw-bold fs-6">Admin Super</div>
                            <div class="text-secondary fs-7">admin@cooca.id</div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fs-7">Role</span>
                            <span
                                class="badge bg-primary-subtle text-primary border border-primary-subtle rounded px-2 py-1 fs-8">System
                                Admin</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fs-7">IP Address</span>
                            <span class="font-monospace fs-7 text-dark">192.168.1.100</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fs-7">User Agent</span>
                            <span class="fs-8 text-secondary text-end text-truncate" style="max-width: 180px;"
                                title="Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0 Safari/5.0">Mozilla/5.0
                                Chrome/126...</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Main Payload Diff -->
            <div class="col-12 col-xl-8 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Changes Inspection (JSON Diff)</h5>
                        <span class="text-secondary fs-7">Comparison between Before and After state</span>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <label class="fw-bold text-danger mb-2 d-block"><i class="bi bi-dash-circle me-1"></i> Old
                                    Values (Before)</label>
                                <pre class="p-3 bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-3 font-monospace fs-7 overflow-auto"
                                    style="max-height: 350px;">{
  "setting_key": "global_tax_rate",
  "value": 10.0,
  "is_active": true,
  "updated_by": 1
}</pre>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="fw-bold text-success mb-2 d-block"><i class="bi bi-plus-circle me-1"></i> New
                                    Values (After)</label>
                                <pre class="p-3 bg-success bg-opacity-10 text-success border border-success-subtle rounded-3 font-monospace fs-7 overflow-auto"
                                    style="max-height: 350px;">{
  "setting_key": "global_tax_rate",
  "value": 11.0,
  "is_active": true,
  "updated_by": 1
}</pre>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Request Metadata -->
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">HTTP Request Metadata</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="text-secondary fs-8 mb-1 d-block">HTTP Method</label>
                                <span class="badge bg-warning text-dark font-monospace px-3 py-1 fs-7">PATCH</span>
                            </div>
                            <div class="col-sm-8">
                                <label class="text-secondary fs-8 mb-1 d-block">Request URL</label>
                                <span
                                    class="font-monospace fs-7 text-dark d-block text-truncate">https://cooca.id/admin/settings/tax-config/update</span>
                            </div>
                            <div class="col-12">
                                <label class="text-secondary fs-8 mb-1 d-block">Full User Agent String</label>
                                <div class="p-2 bg-light rounded border font-monospace fs-8 text-secondary">
                                    Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)
                                    Chrome/126.0.0.0 Safari/537.36
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
