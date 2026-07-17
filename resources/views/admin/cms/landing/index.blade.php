@extends('admin.layouts.app')

@section('title', 'Landing Page CMS')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Landing Page Elements</h2>
                <p class="text-secondary mb-0">Manage content for the main landing page.</p>
            </div>
        </div>

        <!-- Setting Sections (Accordion style bento) -->
        <div class="row g-4">
            <!-- Hero Section -->
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-image me-2 text-primary"></i> Hero Section</h5>
                        <button class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm hover-lift"><i
                                class="bi bi-pencil"></i> Edit</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="text-secondary fs-7 mb-1 d-block">Headline</label>
                            <div class="fw-bold fs-5">Maximize Your Business Potential with ERP Solutions</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary fs-7 mb-1 d-block">Sub-headline</label>
                            <div class="text-secondary">Streamline your operations, boost productivity, and scale seamlessly
                                with our comprehensive ERP platform tailored for your industry.</div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Primary CTA</label>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2">Get Started</span>
                            </div>
                            <div class="col-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Secondary CTA</label>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">Book Demo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-stars me-2 text-warning"></i> Key Features</h5>
                        <button class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm hover-lift"><i
                                class="bi bi-pencil"></i> Edit</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                <div class="bg-primary-subtle text-primary rounded-circle p-2"><i
                                        class="bi bi-lightning-charge"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Lightning Fast</h6>
                                    <p class="text-secondary fs-7 mb-0">Optimized performance for heavy workloads.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                <div class="bg-success-subtle text-success rounded-circle p-2"><i
                                        class="bi bi-shield-check"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Enterprise Security</h6>
                                    <p class="text-secondary fs-7 mb-0">Bank-grade encryption and access controls.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                <div class="bg-info-subtle text-info rounded-circle p-2"><i class="bi bi-graph-up"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Real-time Analytics</h6>
                                    <p class="text-secondary fs-7 mb-0">Live dashboards and custom reporting.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-chat-quote me-2 text-success"></i> Testimonials</h5>
                        <a href="{{ route('admin.testimonials.index') }}"
                            class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm hover-lift">Manage</a>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-secondary mb-4">Manage customer testimonials displayed on the landing page.</p>
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px;">JD</div>
                                <span class="fw-medium">John Doe, TechCorp</span>
                            </div>
                            <span
                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px;">AS</div>
                                <span class="fw-medium">Alice Smith, RetailCo</span>
                            </div>
                            <span
                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Section -->
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-box-arrow-up-right me-2 text-danger"></i> Bottom CTA</h5>
                        <button class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm hover-lift"><i
                                class="bi bi-pencil"></i> Edit</button>
                    </div>
                    <div class="card-body p-4 text-center bg-primary-subtle rounded-bottom-4 border-top">
                        <h4 class="fw-bold text-primary mt-3">Ready to transform your business?</h4>
                        <p class="text-primary mb-4">Join thousands of companies using our ERP today.</p>
                        <button class="btn btn-primary rounded-pill px-4 py-2 shadow-sm mb-3">Start Free Trial</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
