@extends('customer.layouts.app')

@section('title', 'Profile Settings')
@section('subtitle', 'Manage your account information and password.')

@section('content')
    <div class="row g-4">
        <!-- Left: Profile Info -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 glass p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-person me-2"></i> Account Information</h5>

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">Name</label>
                        <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-medium">Phone</label>
                        <input type="text" class="form-control rounded-3 @error('phone') is-invalid @enderror"
                            id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Change Password -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 glass p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2"></i> Change Password</h5>

                <form action="{{ route('customer.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-medium">Current Password</label>
                        <input type="password"
                            class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                            id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">New Password</label>
                        <input type="password" class="form-control rounded-3 @error('password') is-invalid @enderror"
                            id="password" name="password" required minlength="8">
                        <div class="form-text">Minimum 8 characters.</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
                        <input type="password" class="form-control rounded-3" id="password_confirmation"
                            name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
