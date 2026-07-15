@extends('layouts.admin')
@section('title', 'Edit Affiliator')
@section('subtitle', 'Update affiliate partner details')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.affiliators.show', $affiliator) }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Affiliator
        </a>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-pencil-square me-2"></i>Edit: {{ $affiliator->name }}</h5>
                </div>
                <div class="card-saas-body">
                    <form action="{{ route('admin.affiliators.update', $affiliator) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="name">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input @error('name') is-invalid @enderror" type="text"
                                        name="name" id="name" value="{{ old('name', $affiliator->name) }}" required>
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
                                        name="email" id="email" value="{{ old('email', $affiliator->email) }}"
                                        required>
                                    @error('email')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="password">New Password</label>
                                    <input class="form-saas-input @error('password') is-invalid @enderror" type="password"
                                        name="password" id="password" placeholder="Leave blank to keep current">
                                    @error('password')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="password_confirmation">Confirm Password</label>
                                    <input class="form-saas-input" type="password" name="password_confirmation"
                                        id="password_confirmation" placeholder="Leave blank to keep current">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-saas-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            value="1" {{ old('is_active', $affiliator->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label form-saas-label" for="is_active">Active
                                            Account</label>
                                    </div>
                                    <div class="form-saas-hint">Inactive affiliators cannot login or generate referrals
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn-saas btn-saas-primary">
                                <i class="bi bi-floppy me-1"></i> Update Affiliator
                            </button>
                            <a href="{{ route('admin.affiliators.show', $affiliator) }}"
                                class="btn-saas btn-saas-ghost">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-link-45deg me-2"></i>Referral Info</h5>
                </div>
                <div class="card-saas-body">
                    <dl style="font-size:.875rem;margin:0">
                        <dt style="color:var(--text-muted);font-weight:500">Referral Code</dt>
                        <dd class="mb-2"><code>{{ $affiliator->referral_code }}</code></dd>
                        <dt style="color:var(--text-muted);font-weight:500">Joined</dt>
                        <dd class="mb-2">{{ $affiliator->created_at->format('d M Y') }}</dd>
                        <dt style="color:var(--text-muted);font-weight:500">Total Commission</dt>
                        <dd class="mb-0">Rp {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
