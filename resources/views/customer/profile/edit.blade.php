@extends('layouts.customer')
@section('title', 'Profile Settings')
@section('breadcrumb')
    <span class="crumb-current">Profile Settings</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-user" style="color:var(--primary);margin-right:10px;"></i>Profile Settings</h1>
        <p class="page-subtitle">Manage your account information and security settings.</p>
    </div>
</div>

@php $customer = auth('customer')->user(); @endphp

<div class="grid-31">
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- Personal Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Personal Information</div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Full Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'border-danger' : '' }}"
                               value="{{ old('name', $customer->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-input" value="{{ $customer->email }}" disabled style="opacity:.6;cursor:not-allowed;">
                            <div class="form-hint">Email cannot be changed. Contact support.</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-input"
                                   value="{{ old('phone', $customer->phone) }}" placeholder="+62812…">
                            @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="business_name" class="form-input"
                               value="{{ old('business_name', $customer->business_name) }}" placeholder="Your company name">
                    </div>

                    <div class="flex gap-3 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-lock" style="margin-right:8px;"></i>Change Password</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Current Password <span style="color:var(--danger);">*</span></label>
                        <input type="password" name="current_password" class="form-input {{ $errors->has('current_password') ? 'border-danger' : '' }}" required>
                        @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">New Password <span style="color:var(--danger);">*</span></label>
                            <input type="password" name="password" class="form-input" required minlength="8">
                            @error('password') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>

                    <div class="alert alert-primary" style="font-size:12.5px;">
                        <i class="fa-solid fa-circle-info"></i>
                        Password must be at least 8 characters with uppercase, number, and symbol.
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fa-solid fa-shield-check"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- Avatar Card --}}
        <div class="card">
            <div class="card-body text-center">
                <div style="position:relative;display:inline-block;margin-bottom:16px;">
                    <div class="user-avatar" style="width:88px;height:88px;font-size:32px;margin:0 auto;">
                        @if($customer->logo_url)
                            <img src="{{ $customer->logo_url }}" alt="{{ $customer->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        @endif
                    </div>
                </div>
                <div class="font-bold text-lg">{{ $customer->business_name ?? $customer->name }}</div>
                <div class="text-muted text-sm">{{ $customer->email }}</div>
                <div class="mt-3 flex gap-2 justify-center">
                    @if($customer->email_verified_at)
                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Email Verified</span>
                    @else
                        <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Unverified</span>
                    @endif
                    @if($customer->phone_verified_at)
                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Phone Verified</span>
                    @else
                        <span class="badge badge-muted">Phone Unverified</span>
                    @endif
                </div>
                <div class="text-xs text-muted mt-3">
                    Member since {{ $customer->created_at->format('M Y') }}
                </div>
            </div>
        </div>

        {{-- Account Summary --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Account Summary</div>
            </div>
            <div class="card-body">
                <div class="stats-row">
                    <span class="text-sm text-muted">Total Subscriptions</span>
                    <span class="font-bold">{{ $customer->subscriptions()->count() }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Active Licenses</span>
                    <span class="font-bold">{{ $customer->licenses()->where('status', 'active')->count() }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Total Invoices</span>
                    <span class="font-bold">{{ $customer->invoices()->count() }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Support Tickets</span>
                    <span class="font-bold">{{ $customer->tickets()->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card" style="border-color:rgba(var(--danger-rgb),.3);">
            <div class="card-header">
                <div class="card-title text-danger"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>Session</div>
            </div>
            <div class="card-body">
                <div class="text-sm text-muted mb-3">Sign out from all devices or just this session.</div>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger-outline w-full" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
