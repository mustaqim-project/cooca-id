@extends('layouts.customer')
@section('title', 'Company Profile')
@section('breadcrumb')
    <span class="crumb-current">Company Profile</span>
@endsection
@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="fa-solid fa-building" style="color:var(--primary);margin-right:10px;"></i>Company
                Profile</h1>
            <p class="page-subtitle">Configure your company identity, tax details, and branding for invoices and reports.</p>
        </div>
    </div>

    @php
        $customer = auth('customer')->user();
        $profile = $companyProfile ?? $customer->companyProfile;
    @endphp

    <div class="grid-31">
        <div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Business Information</div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success mb-4"><i class="fa-solid fa-check-circle"></i>
                            {{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('customer.company-profile.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Legal Business Name <span
                                        style="color:var(--danger);">*</span></label>
                                <input type="text" name="company_name" class="form-input"
                                    value="{{ old('company_name', $profile?->company_name ?? $customer->business_name) }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tax ID / NPWP</label>
                                <input type="text" name="npwp" class="form-input"
                                    value="{{ old('npwp', $profile?->npwp) }}" placeholder="e.g. 01.234.567.8-901.000">
                                <!-- NOT required -->
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Business Industry <span
                                        style="color:var(--danger);">*</span></label>
                                <select name="industry" class="form-select" required>
                                    <option value="retail"
                                        {{ old('industry', $profile?->industry) === 'retail' ? 'selected' : '' }}>Retail &
                                        E-commerce</option>
                                    <option value="manufacturing"
                                        {{ old('industry', $profile?->industry) === 'manufacturing' ? 'selected' : '' }}>
                                        Manufacturing & Production</option>
                                    <option value="services"
                                        {{ old('industry', $profile?->industry) === 'services' ? 'selected' : '' }}>
                                        Professional Services & Consulting</option>
                                    <option value="technology"
                                        {{ old('industry', $profile?->industry) === 'technology' ? 'selected' : '' }}>
                                        Technology, IT & Software</option>
                                    <option value="construction"
                                        {{ old('industry', $profile?->industry) === 'construction' ? 'selected' : '' }}>
                                        Construction & Real Estate</option>
                                    <option value="healthcare"
                                        {{ old('industry', $profile?->industry) === 'healthcare' ? 'selected' : '' }}>
                                        Healthcare & Medical</option>
                                    <option value="hospitality"
                                        {{ old('industry', $profile?->industry) === 'hospitality' ? 'selected' : '' }}>
                                        Hospitality, Tourism & Food Services</option>
                                    <option value="education"
                                        {{ old('industry', $profile?->industry) === 'education' ? 'selected' : '' }}>
                                        Education & Training</option>
                                    <option value="agriculture"
                                        {{ old('industry', $profile?->industry) === 'agriculture' ? 'selected' : '' }}>
                                        Agriculture, Farming & Forestry</option>
                                    <option value="automotive"
                                        {{ old('industry', $profile?->industry) === 'automotive' ? 'selected' : '' }}>
                                        Automotive, Workshop & Transportation</option>
                                    <option value="finance"
                                        {{ old('industry', $profile?->industry) === 'finance' ? 'selected' : '' }}>
                                        Finance, Banking & Insurance</option>
                                    <option value="logistics"
                                        {{ old('industry', $profile?->industry) === 'logistics' ? 'selected' : '' }}>
                                        Logistics & Supply Chain</option>
                                    <option value="wholesale"
                                        {{ old('industry', $profile?->industry) === 'wholesale' ? 'selected' : '' }}>
                                        Wholesale & Distribution</option>
                                    <option value="creative"
                                        {{ old('industry', $profile?->industry) === 'creative' ? 'selected' : '' }}>
                                        Entertainment, Media & Creative</option>
                                    <option value="other"
                                        {{ old('industry', $profile?->industry) === 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Company Size <span style="color:var(--danger);">*</span></label>
                                <select name="company_size" class="form-select" required>
                                    <option value="1-10"
                                        {{ old('company_size', $profile?->company_size) === '1-10' ? 'selected' : '' }}>1-10
                                        Employees</option>
                                    <option value="11-50"
                                        {{ old('company_size', $profile?->company_size) === '11-50' ? 'selected' : '' }}>
                                        11-50 Employees</option>
                                    <option value="51-200"
                                        {{ old('company_size', $profile?->company_size) === '51-200' ? 'selected' : '' }}>
                                        51-200 Employees</option>
                                    <option value="201+"
                                        {{ old('company_size', $profile?->company_size) === '201+' ? 'selected' : '' }}>
                                        200+ Enterprise</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Company Phone <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="phone" class="form-input"
                                    value="{{ old('phone', $profile?->phone ?? $customer->phone) }}" placeholder="e.g. +628123456789" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-input"
                                    value="{{ old('website', $profile?->website) }}" placeholder="https://example.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Street Address <span style="color:var(--danger);">*</span></label>
                            <textarea name="address" class="form-textarea" rows="3" placeholder="Full address for billing & invoices"
                                required>{{ old('address', $profile?->address) }}</textarea>
                        </div>

                        <div class="grid-3">
                            <div class="form-group">
                                <label class="form-label">City <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="city" class="form-input"
                                    value="{{ old('city', $profile?->city) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Province / State <span
                                        style="color:var(--danger);">*</span></label>
                                <input type="text" name="province" class="form-input"
                                    value="{{ old('province', $profile?->province) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="postal_code" class="form-input"
                                    value="{{ old('postal_code', $profile?->postal_code) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Company Logo</label>
                            <input type="file" name="logo" class="form-input" accept="image/*">
                            <div class="form-hint">Upload PNG or JPG (max 2MB). Used on custom reports & invoices.</div>
                            <!-- NOT required -->
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fa-solid fa-floppy-disk"></i> Update Company Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-body text-center">
                    <div
                        style="width:72px;height:72px;border-radius:16px;background:var(--primary-light);color:var(--primary);font-size:32px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="font-bold text-lg">{{ $profile?->company_name ?? ($customer->business_name ?? 'Company') }}
                    </div>
                    <div class="text-sm text-muted mt-1">
                        {{ $profile?->industry ? ucfirst($profile->industry) : 'Enterprise Client' }}</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">💡 Why update this?</div>
                </div>
                <div class="card-body text-xs text-muted" style="line-height:1.6;">
                    Your company name, NPWP, and address will automatically populate tax invoices (Faktur Pajak) and
                    official receipts generated by COOCA.ID.
                </div>
            </div>
        </div>
    </div>
@endsection
