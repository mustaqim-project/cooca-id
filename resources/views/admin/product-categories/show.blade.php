@extends('admin.layouts.app')

@section('title', 'Product Categories Details')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="#" class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">product categories Details</h2>
                <p class="text-secondary mb-0">View full information and activity.</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                <i class="bi bi-pencil me-2"></i> Edit
            </a>
            <button class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm">
                <i class="bi bi-trash me-2"></i> Delete
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Info -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                    <i class="bi bi-card-text fs-1"></i>
                </div>
                <h4 class="fw-bold mb-1">Sample Data Entry</h4>
                <p class="text-secondary mb-3">ID: #001</p>
                <div>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Active</span>
                </div>

                <hr class="border-light my-4">

                <div class="d-flex justify-content-between text-start mb-3">
                    <span class="text-secondary fs-7">Created At</span>
                    <span class="fw-medium fs-7">Oct 12, 2026</span>
                </div>
                <div class="d-flex justify-content-between text-start">
                    <span class="text-secondary fs-7">Last Updated</span>
                    <span class="fw-medium fs-7">Oct 15, 2026</span>
                </div>
            </div>
        </div>

        <!-- Main Details -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 glass h-100">
                <div class="card-header bg-transparent border-bottom border-light p-4">
                    <h5 class="fw-bold mb-0">Detailed Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="text-secondary fs-7 mb-1 d-block">Description</label>
                            <div class="p-3 bg-light rounded-3 border">
                                This is a sample description detailing the specific record. It contains relevant information that users need to see when viewing this specific entry in the system.
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-secondary fs-7 mb-1 d-block">Additional Data 1</label>
                            <div class="fw-medium">Value goes here</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-secondary fs-7 mb-1 d-block">Additional Data 2</label>
                            <div class="fw-medium">Value goes here</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection