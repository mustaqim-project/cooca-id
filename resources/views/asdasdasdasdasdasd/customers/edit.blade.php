@extends('layouts.admin')
@section('title', 'Edit Customer')
@section('subtitle', 'Update customer account details')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.show', $customer) }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Customer
        </a>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-pencil-square me-2"></i>Edit: {{ $customer->name }}</h5>
                </div>
                <div class="card-saas-body">
                    <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="name">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input @error('name') is-invalid @enderror" type="text"
                                        name="name" id="name" value="{{ old('name', $customer->name) }}" required>
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
                                        name="email" id="email" value="{{ old('email', $customer->email) }}" required>
                                    @error('email')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="phone">Phone Number</label>
                                    <input class="form-saas-input @error('phone') is-invalid @enderror" type="text"
                                        name="phone" id="phone" value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="business_name">Business Name</label>
                                    <input class="form-saas-input @error('business_name') is-invalid @enderror"
                                        type="text" name="business_name" id="business_name"
                                        value="{{ old('business_name', $customer->business_name) }}">
                                    @error('business_name')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <hr style="border-color:var(--border-color)">
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
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn-saas btn-saas-primary">
                                <i class="bi bi-floppy me-1"></i> Update Customer
                            </button>
                            <a href="{{ route('admin.customers.show', $customer) }}"
                                class="btn-saas btn-saas-ghost">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-person-circle me-2"></i>Account Info</h5>
                </div>
                <div class="card-saas-body">
                    <dl style="font-size:.875rem;margin:0">
                        <dt style="color:var(--text-muted);font-weight:500">Registered</dt>
                        <dd class="mb-2">{{ $customer->created_at->format('d M Y') }}</dd>
                        <dt style="color:var(--text-muted);font-weight:500">Last Login</dt>
                        <dd class="mb-2">
                            {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never' }}</dd>
                        <dt style="color:var(--text-muted);font-weight:500">Status</dt>
                        <dd class="mb-0">
                            @if ($customer->email_verified_at)
                                <span class="badge-saas badge-saas-success">Verified</span>
                            @else
                                <span class="badge-saas badge-saas-warning">Unverified</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
