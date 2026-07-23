@extends('customer.layouts.app')

@section('title', 'Request Free Trial')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.trials.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Request Free Trial</h2>
                    <p class="text-secondary mb-0">Select a product and configure your trial environment.</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <div class="card-header bg-transparent border-bottom border-light p-4">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-magic me-2"></i> Trial Configuration</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('customer.trials.store') }}" method="POST">
                        @csrf
                        
                        <!-- Product Selection -->
                        <div class="mb-5">
                            <label class="form-label fw-medium text-secondary mb-3">Select Product <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach ($products as $product)
                                    <div class="col-md-6">
                                        <div class="form-check custom-radio-card m-0 p-0 position-relative">
                                            <input type="radio" name="product_id" id="product_{{ $product->id }}" value="{{ $product->id }}" class="form-check-input position-absolute opacity-0" required>
                                            <label class="card bg-light border-light hover-lift h-100 cursor-pointer p-4 d-flex flex-row align-items-center justify-content-between rounded-4" for="product_{{ $product->id }}">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                                                    <p class="text-secondary fs-7 mb-0">{{ Str::limit($product->description, 50) }}</p>
                                                </div>
                                                <i class="bi bi-check-circle-fill text-primary fs-4 custom-radio-icon ms-3 d-none"></i>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <style>
                                .custom-radio-card input:checked + label {
                                    border-color: var(--bs-primary) !important;
                                    background-color: var(--bs-primary-bg-subtle) !important;
                                }
                                .custom-radio-card input:checked + label .custom-radio-icon {
                                    display: block !important;
                                }
                            </style>
                        </div>
                        
                        <!-- Subdomain -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary">Requested Subdomain <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="requested_subdomain" class="form-control bg-light border-light py-2" pattern="[a-z0-9-]+" title="Only lowercase letters, numbers, and dashes are allowed" required>
                                <span class="input-group-text bg-white border-light text-secondary">.cooca.id</span>
                            </div>
                            <div class="form-text mt-2 text-secondary fs-7">Only lowercase letters, numbers, and hyphens.</div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary">Additional Notes</label>
                            <textarea name="notes" rows="4" class="form-control bg-light border-light py-2" placeholder="Any specific requirements or questions?"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top border-light">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 hover-lift fw-medium">
                                Submit Request <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
