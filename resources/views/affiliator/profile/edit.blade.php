@extends('affiliator.layouts.app')

@section('title', 'Profile Settings')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Profile & Bank Settings</span>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Account & Bank Settings</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Update your personal details, payout bank account, and security settings.</p>
    </div>
</div>

<div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));gap:24px;">

    {{-- Account Info --}}
    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-user-gear" style="color:var(--primary);"></i>
                Account Information
            </div>
        </div>
        <div class="portal-card-body">
            <form action="{{ route('affiliator.profile.update') }}" method="POST" style="display:flex;flex-direction:column;gap:16px;">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div style="padding:10px 14px;background:rgba(16,185,129,.1);border:1px solid var(--success);border-radius:var(--radius-sm);color:var(--success);font-size:13px;font-weight:600;">
                        <i class="fa-solid fa-check-circle" style="margin-right:6px;"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-input @error('name') is-invalid @enderror"
                           value="{{ old('name', $user['name'] ?? $user->name ?? '') }}" required>
                    @error('name')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input @error('email') is-invalid @enderror"
                           value="{{ old('email', $user['email'] ?? $user->email ?? '') }}" required>
                    @error('email')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                    <button type="submit" class="btn btn-p">
                        <i class="fa-solid fa-floppy-disk"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bank Account Settings --}}
    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-building-columns" style="color:var(--accent);"></i>
                Payout Bank Account
            </div>
        </div>
        <div class="portal-card-body">
            <form action="{{ route('affiliator.profile.bank_account.update') }}" method="POST" style="display:flex;flex-direction:column;gap:16px;">
                @csrf
                @method('PUT')

                @if(session('bank_success'))
                    <div style="padding:10px 14px;background:rgba(16,185,129,.1);border:1px solid var(--success);border-radius:var(--radius-sm);color:var(--success);font-size:13px;font-weight:600;">
                        <i class="fa-solid fa-check-circle" style="margin-right:6px;"></i> {{ session('bank_success') }}
                    </div>
                @endif

                @php
                    $aff = auth('affiliator')->user() ?? auth()->user();
                @endphp

                <div class="form-group">
                    <label for="bank_name" class="form-label">Bank / Provider</label>
                    <select name="bank_name" id="bank_name" class="form-select @error('bank_name') is-invalid @enderror" required>
                        <option value="">Select Bank</option>
                        <option value="BCA" {{ old('bank_name', $aff?->bank_name) == 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="Mandiri" {{ old('bank_name', $aff?->bank_name) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BNI" {{ old('bank_name', $aff?->bank_name) == 'BNI' ? 'selected' : '' }}>BNI</option>
                        <option value="BRI" {{ old('bank_name', $aff?->bank_name) == 'BRI' ? 'selected' : '' }}>BRI</option>
                        <option value="BSI" {{ old('bank_name', $aff?->bank_name) == 'BSI' ? 'selected' : '' }}>BSI</option>
                        <option value="OVO" {{ old('bank_name', $aff?->bank_name) == 'OVO' ? 'selected' : '' }}>OVO</option>
                        <option value="GoPay" {{ old('bank_name', $aff?->bank_name) == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                        <option value="Dana" {{ old('bank_name', $aff?->bank_name) == 'Dana' ? 'selected' : '' }}>Dana</option>
                    </select>
                    @error('bank_name')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bank_account" class="form-label">Account Number</label>
                    <input type="text" name="bank_account" id="bank_account" class="form-input @error('bank_account') is-invalid @enderror"
                           value="{{ old('bank_account', $aff?->bank_account) }}" placeholder="e.g. 1234567890" required>
                    @error('bank_account')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                    <button type="submit" class="btn btn-p">
                        <i class="fa-solid fa-floppy-disk"></i> Save Bank Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Security / Change Password --}}
    <div class="portal-card" style="grid-column: span 2;">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-shield-halved" style="color:var(--warning);"></i>
                Security & Password
            </div>
        </div>
        <div class="portal-card-body">
            <form action="{{ route('affiliator.profile.password.update') }}" method="POST" style="display:flex;flex-direction:column;gap:16px;max-width:540px;">
                @csrf
                @method('PUT')

                @if(session('password_success'))
                    <div style="padding:10px 14px;background:rgba(16,185,129,.1);border:1px solid var(--success);border-radius:var(--radius-sm);color:var(--success);font-size:13px;font-weight:600;">
                        <i class="fa-solid fa-check-circle" style="margin-right:6px;"></i> {{ session('password_success') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-input @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-input @error('new_password') is-invalid @enderror" required minlength="8">
                        @error('new_password')
                            <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-input" required>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                    <button type="submit" class="btn btn-p">
                        <i class="fa-solid fa-key"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
