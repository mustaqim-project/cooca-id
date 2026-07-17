@extends('layouts.admin')
@section('title', 'Add Affiliator')
@section('subtitle', 'Create a new affiliate partner')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.affiliators.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Affiliators
        </a>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-person-plus me-2"></i>Affiliator Information</h5>
                </div>
                <div class="card-saas-body">
                    <form action="{{ route('admin.affiliators.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="name">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input @error('name') is-invalid @enderror" type="text"
                                        name="name" id="name" value="{{ old('name') }}" placeholder="Partner Name"
                                        required>
                                    @error('name')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="email">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input @error('email') is-invalid @enderror" type="email"
                                        name="email" id="email" value="{{ old('email') }}"
                                        placeholder="partner@example.com" required>
                                    @error('email')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="password">Password <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input @error('password') is-invalid @enderror" type="password"
                                        name="password" id="password" placeholder="Min. 8 characters" required>
                                    @error('password')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="password_confirmation">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input" type="password" name="password_confirmation"
                                        id="password_confirmation" placeholder="Repeat password" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn-saas btn-saas-primary">
                                <i class="bi bi-floppy me-1"></i> Save Affiliator
                            </button>
                            <a href="{{ route('admin.affiliators.index') }}" class="btn-saas btn-saas-ghost">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-info-circle me-2"></i>Notes</h5>
                </div>
                <div class="card-saas-body">
                    <ul class="list-unstyled mb-0" style="font-size:.875rem;color:var(--text-muted);line-height:1.8">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Referral code auto-generated</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Affiliator can login separately</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Commission tracked automatically</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
