@extends('affiliator.layouts.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Profile Settings</h2>
                <p class="text-secondary mb-0">Manage your account information and password.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left: Profile Info -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4 d-flex align-items-center gap-2">
                        <i class="bi bi-person text-primary fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark">Account Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('affiliator.profile.update') }}" method="POST" class="d-flex flex-column gap-4">
                            @csrf
                            @method('PUT')

                            @if(session('success'))
                                <div class="alert alert-success rounded-3 fs-7 border-0 shadow-sm">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            <div>
                                <label for="name" class="form-label fw-medium text-dark">Full Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control bg-light border-light @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user['name'] ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="form-label fw-medium text-dark">Email Address</label>
                                <input type="email" name="email" id="email"
                                    class="form-control bg-light border-light @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user['email'] ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                                    <i class="bi bi-check2-circle me-1"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Change Password -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-primary fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark">Change Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('affiliator.profile.password.update') }}" method="POST" class="d-flex flex-column gap-4">
                            @csrf
                            @method('PUT')

                            @if(session('password_success'))
                                <div class="alert alert-success rounded-3 fs-7 border-0 shadow-sm">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('password_success') }}
                                </div>
                            @endif

                            <div>
                                <label for="current_password" class="form-label fw-medium text-dark">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control bg-light border-light @error('current_password') is-invalid @enderror"
                                    required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="new_password" class="form-label fw-medium text-dark">New Password</label>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control bg-light border-light @error('new_password') is-invalid @enderror"
                                    required minlength="8">
                                <div class="form-text text-secondary fs-7"><i class="bi bi-info-circle me-1"></i> Minimum 8 characters.</div>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="form-label fw-medium text-dark">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-control bg-light border-light" required>
                            </div>

                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                                    <i class="bi bi-shield-check me-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
