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
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Request Payout Withdrawal</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Transfer your available cleared balance directly to your bank account.</p>
    </div>
    <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Withdrawals
    </a>
</div>

<form action="{{ route('affiliator.withdrawals.store') }}" method="POST">
    @csrf

    <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));gap:24px;">

        {{-- Left: Balance & Form inputs --}}
        <div style="grid-column: span 2;">

            {{-- Available Balance Banner --}}
            <div style="background:linear-gradient(135deg, var(--primary), var(--accent));border-radius:var(--radius);padding:20px;color:#fff;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;opacity:.85;">Withdrawable Balance</div>
                    <div style="font-size:28px;font-weight:900;margin-top:4px;">Rp {{ number_format($available_balance ?? 0, 0, ',', '.') }}</div>
                </div>

                @if(($available_balance ?? 0) < ($min_withdrawal ?? 50000))
                    <div style="background:rgba(255,255,255,.2);backdrop-filter:blur(4px);padding:8px 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>
                        Minimum payout: Rp {{ number_format($min_withdrawal ?? 50000, 0, ',', '.') }}
                    </div>
                @endif
            </div>

            {{-- Form Details Card --}}
            <div class="portal-card">
                <div class="portal-card-header">
                    <div class="portal-card-title">
                        <i class="fa-solid fa-file-signature" style="color:var(--primary);"></i>
                        Payout Details
                    </div>
                </div>

                <div class="portal-card-body">
                    @if(session('error'))
                        <div style="padding:12px 16px;background:rgba(239,68,68,.1);border:1px solid var(--danger);border-radius:var(--radius-sm);color:var(--danger);font-size:13px;margin-bottom:16px;font-weight:600;">
                            <i class="fa-solid fa-circle-xmark" style="margin-right:6px;"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div style="display:flex;flex-direction:column;gap:16px;">

                        {{-- Amount --}}
                        <div class="form-group">
                            <label class="form-label" for="amount">Withdrawal Amount (Rp)</label>
                            <input type="number" name="amount" id="amount"
                                   class="form-input @error('amount') is-invalid @enderror"
                                   value="{{ old('amount', $available_balance ?? 0) }}"
                                   min="{{ $min_withdrawal ?? 50000 }}"
                                   max="{{ $available_balance ?? 0 }}"
                                   placeholder="Enter withdrawal amount" required>
                            @error('amount')
                                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                            @enderror
                            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                                You may withdraw up to your total available balance.
                            </div>
                        </div>

                        <hr style="border:none;border-top:1px solid var(--border);margin:8px 0;">

                        {{-- Bank Details Header --}}
                        <div>
                            <h4 style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:4px;">Bank Account Destination</h4>
                            @if(empty($bankAccount))
                                <div style="padding:12px;background:rgba(245,158,11,.1);border:1px solid var(--warning);border-radius:var(--radius-sm);color:var(--warning);font-size:12px;margin-top:8px;">
                                    <i class="fa-solid fa-triangle-exclamation" style="margin-right:4px;"></i>
                                    Please verify or configure your bank account details in your profile settings.
                                </div>
                            @endif
                        </div>

                        <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
                            {{-- Bank / Method --}}
                            <div class="form-group">
                                <label class="form-label" for="withdrawal_method">Bank Name</label>
                                <select name="withdrawal_method" id="withdrawal_method" class="form-select @error('withdrawal_method') is-invalid @enderror" required>
                                    <option value="">Select Bank</option>
                                    <option value="BCA" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BCA') ? 'selected' : '' }}>BCA</option>
                                    <option value="Mandiri" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'Mandiri') ? 'selected' : '' }}>Mandiri</option>
                                    <option value="BNI" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BNI') ? 'selected' : '' }}>BNI</option>
                                    <option value="BRI" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BRI') ? 'selected' : '' }}>BRI</option>
                                    <option value="BSI" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'BSI') ? 'selected' : '' }}>BSI</option>
                                    <option value="OVO" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'OVO') ? 'selected' : '' }}>OVO</option>
                                    <option value="GoPay" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'GoPay') ? 'selected' : '' }}>GoPay</option>
                                    <option value="Dana" {{ (old('withdrawal_method', $bankAccount['bank_name'] ?? '') == 'Dana') ? 'selected' : '' }}>Dana</option>
                                </select>
                                @error('withdrawal_method')
                                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
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
                                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
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
                                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Actions & Rules --}}
        <div>
            <div class="portal-card mb-6">
                <div class="portal-card-header">
                    <div class="portal-card-title">
                        <i class="fa-solid fa-paper-plane" style="color:var(--primary);"></i>
                        Confirm Payout
                    </div>
                </div>

                <div class="portal-card-body">
                    <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:16px;">Double check your payout details before submitting.</p>

                    <button type="submit" class="btn btn-p btn-lg" style="width:100%;justify-content:center;"
                        {{ ($available_balance ?? 0) < ($min_withdrawal ?? 50000) ? 'disabled' : '' }}>
                        <i class="fa-solid fa-paper-plane"></i> Submit Payout Request
                    </button>

                    <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-s" style="width:100%;justify-content:center;margin-top:8px;">
                        Cancel
                    </a>

                    <div style="margin-top:20px;padding:12px;background:var(--bg);border-radius:var(--radius-sm);border:1px solid var(--border);display:flex;gap:10px;">
                        <i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);font-size:16px;margin-top:2px;"></i>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:var(--text);">Processing Time</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Payout requests are audited and processed within 1-2 business days.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
