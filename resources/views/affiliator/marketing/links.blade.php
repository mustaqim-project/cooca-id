@extends('affiliator.layouts.app')

@section('title', 'Marketing Links')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.marketing.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Marketing Links</h2>
                    <p class="text-secondary mb-0">Custom tracking links for your campaigns.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-plus-lg me-1"></i> Create Custom Link
                </button>
            </div>
        </div>

        <!-- Coming Soon -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="card-body py-5 text-center text-secondary">
                <div class="mb-3 d-inline-flex bg-light rounded-circle p-4">
                    <i class="bi bi-link-45deg fs-1 text-secondary"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark">Custom Link Builder Coming Soon</h5>
                <p class="mb-0 mx-auto" style="max-width: 500px;">
                    Soon you'll be able to create custom tracking links to monitor the performance of specific marketing campaigns.
                </p>
            </div>
        </div>
    </div>
@endsection
