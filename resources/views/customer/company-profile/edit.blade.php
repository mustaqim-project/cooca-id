@extends('customer.layouts.app')

@section('title', 'Company Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="mb-4">
                <h2 class="mb-1 fw-bold">Company Profile</h2>
                <p class="text-secondary mb-0">Manage your corporate organization identity and billing details.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass">
                <div class="card-header bg-transparent border-bottom border-light p-4">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-building me-2"></i> Organization Details</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('customer.company-profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Company Name *</label>
                                <input type="text" name="company_name"
                                    value="{{ old('company_name', $companyProfile->company_name) }}" required
                                    class="form-control rounded-3 bg-transparent">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Industry</label>
                                <input type="text" name="industry" value="{{ old('industry', $companyProfile->industry) }}"
                                    class="form-control rounded-3 bg-transparent">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Company Size</label>
                                <select name="company_size" class="form-select rounded-3 bg-transparent">
                                    <option value="">Select size...</option>
                                    @foreach (['1-10', '11-50', '51-200', '201-500', '500+'] as $size)
                                        <option value="{{ $size }}" @selected(old('company_size', $companyProfile->company_size) === $size)>{{ $size }}
                                            employees</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $companyProfile->phone) }}"
                                    class="form-control rounded-3 bg-transparent">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">NPWP (Tax ID)</label>
                                <input type="text" name="npwp" value="{{ old('npwp', $companyProfile->npwp) }}"
                                    class="form-control rounded-3 bg-transparent">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Website</label>
                                <input type="url" name="website" value="{{ old('website', $companyProfile->website) }}"
                                    class="form-control rounded-3 bg-transparent">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Address</label>
                                <textarea name="address" rows="3"
                                    class="form-control rounded-3 bg-transparent">{{ old('address', $companyProfile->address) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">City</label>
                                <input type="text" name="city" value="{{ old('city', $companyProfile->city) }}"
                                    class="form-control rounded-3 bg-transparent">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Province</label>
                                <input type="text" name="province" value="{{ old('province', $companyProfile->province) }}"
                                    class="form-control rounded-3 bg-transparent">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
