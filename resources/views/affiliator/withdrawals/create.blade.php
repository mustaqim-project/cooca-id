@extends('affiliator.layouts.app')

@section('title', 'Request Withdrawal')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.withdrawals.index') }}" class="crumb-link">Withdrawals</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Request Payout</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Request Payout Withdrawal</div>
            <div class="page-subtitle">Transfer your available cleared balance directly to your bank account.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Withdrawals
            </a>
        </div>
    </div>

    <form action="{{ route('affiliator.withdrawals.store') }}" method="POST">
        @csrf

        <div class="grid-31" style="gap:24px;">

            {{-- LEFT: Balance & Form inputs --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Available Balance Banner --}}
                <div
                    style="background:linear-gradient(135deg, var(--primary), var(--accent));border-radius:var(--radius);padding:20px;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;opacity:.85;">Withdrawable
                            Balance</div>
                        <div style="font-size:28px;font-weight:900;margin-top:4px;">Rp
                            {{ number_format($availableBalance ?? 0, 0, ',', '.') }}</div>
                    </div>

                    @if (($availableBalance ?? 0) < ($minimumWithdrawal ?? 50000))
                        <div
                            style="background:rgba(255,255,255,.2);backdrop-filter:blur(4px);padding:8px 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;">
                            <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>
                            Minimum payout: Rp {{ number_format($minimumWithdrawal ?? 50000, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                {{-- Form Details Card --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-file-signature"
                                style="color:var(--primary);margin-right:8px;"></i>Payout Details</div>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger" style="margin-bottom:16px;">
                                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                            </div>
                        @endif

                        <div style="display:flex;flex-direction:column;gap:16px;">

                            {{-- Amount --}}
                            <div class="form-group">
                                <label class="form-label" for="amount">Withdrawal Amount (Rp)</label>
                                <input type="number" name="amount" id="amount"
                                    class="form-input @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $availableBalance ?? 0) }}"
                                    min="{{ $minimumWithdrawal ?? 50000 }}" max="{{ $availableBalance ?? 0 }}"
                                    placeholder="Enter withdrawal amount" required>
                                @error('amount')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">
                                    You may withdraw up to your total available balance.
                                </div>
                            </div>

                            <hr class="divider" style="margin:8px 0;">

                            {{-- Bank Details Header --}}
                            <div>
                                <h4 style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:4px;">Bank Account
                                    Destination</h4>
                                @if (empty($bankAccount))
                                    <div class="alert alert-warning" style="margin-top:8px;margin-bottom:0;">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        Please verify or configure your bank account details in your profile settings.
                                    </div>
                                @endif
                            </div>

                            <div class="grid-2" style="gap:16px;">
                                {{-- Bank / Method --}}
                                <div class="form-group">
                                    <label class="form-label" for="withdrawal_method">Bank Name</label>
                                    <select name="withdrawal_method" id="withdrawal_method"
                                        class="form-select @error('withdrawal_method') is-invalid @enderror" required>
                                        <option value="">Select Bank</option>
                                        <option value="BCA"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BCA' ? 'selected' : '' }}>
                                            BCA</option>
                                        <option value="Mandiri"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                            Mandiri</option>
                                        <option value="BNI"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BNI' ? 'selected' : '' }}>
                                            BNI</option>
                                        <option value="BRI"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BRI' ? 'selected' : '' }}>
                                            BRI</option>
                                        <option value="BSI"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BSI' ? 'selected' : '' }}>
                                            BSI</option>
                                        <option value="OVO"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'OVO' ? 'selected' : '' }}>
                                            OVO</option>
                                        <option value="GoPay"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'GoPay' ? 'selected' : '' }}>
                                            GoPay</option>
                                        <option value="Dana"
                                            {{ old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'Dana' ? 'selected' : '' }}>
                                            Dana</option>
                                    </select>
                                    @error('withdrawal_method')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Account Number --}}
                                <div class="form-group">
                                    <label class="form-label" for="account_number">Account Number</label>
                                    <input type="text" name="account_number" id="account_number"
                                        class="form-input @error('account_number') is-invalid @enderror"
                                        value="{{ old('account_number', $bankAccount['account_number'] ?? '') }}"
                                        placeholder="e.g. 1234567890" required>
                                    @error('account_number')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Account Name --}}
                            <div class="form-group">
                                <label class="form-label" for="account_name">Account Holder Name</label>
                                <input type="text" name="account_name" id="account_name"
                                    class="form-input @error('account_name') is-invalid @enderror"
                                    value="{{ old('account_name', $bankAccount['account_name'] ?? '') }}"
                                    placeholder="Full name matching bank account" required>
                                @error('account_name')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Actions & Rules --}}
            <div style="display:flex;flex-direction:column;gap:24px;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-paper-plane"
                                style="color:var(--primary);margin-right:8px;"></i>Confirm Payout</div>
                    </div>

                    <div class="card-body">
                        <p class="text-sm text-muted mb-4">Double check your payout details before submitting.</p>

                        <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;"
                            {{ ($availableBalance ?? 0) < ($minimumWithdrawal ?? 50000) ? 'disabled' : '' }}>
                            <i class="fa-solid fa-paper-plane"></i> Submit Payout Request
                        </button>

                        <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-outline"
                            style="width:100%;justify-content:center;margin-top:8px;">
                            Cancel
                        </a>

                        <div
                            style="margin-top:20px;padding:12px;background:var(--bg);border-radius:var(--radius-sm);border:1px solid var(--border);display:flex;gap:10px;">
                            <i class="fa-solid fa-clock-rotate-left"
                                style="color:var(--primary);font-size:16px;margin-top:2px;"></i>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--text);">Processing Time</div>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Payout requests are
                                    audited and processed within 1-2 business days.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
