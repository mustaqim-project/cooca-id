@extends('affiliator.layouts.app')

@section('title', 'Profile Settings')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Profile & Bank Settings</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Account & Bank Settings</div>
            <div class="page-subtitle">Update your personal details, payout bank account, and security settings.</div>
        </div>
    </div>

    <div class="grid-31" style="gap:24px;">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Account Info --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-user-gear"
                            style="color:var(--primary);margin-right:8px;"></i>Account Information</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliator.profile.update') }}" method="POST"
                        style="display:flex;flex-direction:column;gap:16px;">
                        @csrf
                        @method('PUT')

                        @if (session('success'))
                            <div class="alert alert-success" style="margin-bottom:0;">
                                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name"
                                class="form-input @error('name') is-invalid @enderror"
                                value="{{ old('name', $user['name'] ?? ($user->name ?? '')) }}" required>
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="form-input @error('email') is-invalid @enderror"
                                value="{{ old('email', $user['email'] ?? ($user->email ?? '')) }}" required>
                            @error('email')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security / Change Password --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-shield-halved"
                            style="color:var(--warning);margin-right:8px;"></i>Security & Password</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliator.profile.password.update') }}" method="POST"
                        style="display:flex;flex-direction:column;gap:16px;max-width:540px;">
                        @csrf
                        @method('PUT')

                        @if (session('password_success'))
                            <div class="alert alert-success" style="margin-bottom:0;">
                                <i class="fa-solid fa-check-circle"></i> {{ session('password_success') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" name="current_password" id="current_password"
                                class="form-input @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid-2" style="gap:16px;">
                            <div class="form-group">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-input @error('new_password') is-invalid @enderror" required minlength="8">
                                @error('new_password')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-input" required>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Bank Account Settings --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-building-columns"
                            style="color:var(--accent);margin-right:8px;"></i>Payout Bank Account</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliator.profile.bank_account.update') }}" method="POST"
                        style="display:flex;flex-direction:column;gap:16px;">
                        @csrf
                        @method('PUT')

                        @if (session('bank_success'))
                            <div class="alert alert-success" style="margin-bottom:0;">
                                <i class="fa-solid fa-check-circle"></i> {{ session('bank_success') }}
                            </div>
                        @endif

                        @php
                            $aff = auth('affiliator')->user() ?? auth()->user();
                        @endphp

                        <div class="form-group">
                            <label for="bank_name" class="form-label">Bank / Provider</label>
                            <select name="bank_name" id="bank_name"
                                class="form-select @error('bank_name') is-invalid @enderror" required>
                                <option value="">Select Bank</option>
                                <option value="BCA"
                                    {{ old('bank_name', $aff?->bank_name) == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="Mandiri"
                                    {{ old('bank_name', $aff?->bank_name) == 'Mandiri' ? 'selected' : '' }}>Mandiri
                                </option>
                                <option value="BNI"
                                    {{ old('bank_name', $aff?->bank_name) == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="BRI"
                                    {{ old('bank_name', $aff?->bank_name) == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BSI"
                                    {{ old('bank_name', $aff?->bank_name) == 'BSI' ? 'selected' : '' }}>BSI</option>
                                <option value="OVO"
                                    {{ old('bank_name', $aff?->bank_name) == 'OVO' ? 'selected' : '' }}>OVO</option>
                                <option value="GoPay"
                                    {{ old('bank_name', $aff?->bank_name) == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                                <option value="Dana"
                                    {{ old('bank_name', $aff?->bank_name) == 'Dana' ? 'selected' : '' }}>Dana</option>
                            </select>
                            @error('bank_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="account_number" class="form-label">Account Number</label>
                            <input type="text" name="account_number" id="account_number"
                                class="form-input @error('account_number') is-invalid @enderror"
                                value="{{ old('account_number', $aff?->bank_account) }}" placeholder="e.g. 1234567890"
                                required>
                            @error('account_number')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Save Bank Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
