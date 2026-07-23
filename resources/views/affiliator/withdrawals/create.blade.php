@extends('affiliator.layouts.app')

@section('title', 'Request Withdrawal')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Request Withdrawal</h2>
                    <p class="text-secondary mb-0">Withdraw your cleared commissions to your bank account.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('affiliator.withdrawals.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <!-- Available Balance Card -->
                    <div class="card border-0 shadow-sm rounded-4 glass mb-4" style="background: linear-gradient(135deg, rgba(var(--color-primary-rgb), 0.1) 0%, rgba(var(--color-primary-rgb), 0.05) 100%); border: 1px solid rgba(var(--color-primary-rgb), 0.2) !important;">
                        <div class="card-body p-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-4">
                            <div class="d-flex align-items-center gap-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px;">
                                    <i class="bi bi-wallet2 fs-2"></i>
                                </div>
                                <div>
                                    <p class="text-secondary fs-7 text-uppercase fw-semibold mb-1">Available Balance</p>
                                    <h2 class="fw-bold text-dark mb-0">Rp {{ number_format($available_balance ?? 0, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                            
                            @if(($available_balance ?? 0) < ($min_withdrawal ?? 50000))
                            <div class="alert bg-warning-subtle text-warning border-warning-subtle mb-0 py-2 px-3 fs-7 rounded-3 d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle"></i>
                                Minimum withdrawal is Rp {{ number_format($min_withdrawal ?? 50000, 0, ',', '.') }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Withdrawal Form -->
                    <div class="card border-0 shadow-sm rounded-4 glass">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0 text-dark">Withdrawal Details</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            @if(session('error'))
                                <div class="alert alert-danger rounded-3 fs-7 border-0 shadow-sm mb-4">
                                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                                </div>
                            @endif

                            <div class="row g-4">
                                <!-- Amount -->
                                <div class="col-12">
                                    <label for="amount" class="form-label fw-medium text-dark">Withdrawal Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-light text-secondary">Rp</span>
                                        <input type="number" name="amount" id="amount" 
                                            class="form-control bg-light border-light @error('amount') is-invalid @enderror" 
                                            value="{{ old('amount', $available_balance ?? 0) }}" 
                                            min="{{ $min_withdrawal ?? 50000 }}" 
                                            max="{{ $available_balance ?? 0 }}"
                                            placeholder="Enter amount" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text mt-2 text-secondary fs-7">
                                        <i class="bi bi-info-circle me-1"></i> You can withdraw up to your total available balance.
                                    </div>
                                </div>

                                <div class="col-12"><hr class="border-light my-2"></div>

                                <!-- Bank Details Header -->
                                <div class="col-12">
                                    <h6 class="fw-bold text-dark mb-3">Destination Account</h6>
                                    
                                    @if(empty($bankAccount))
                                    <div class="alert bg-warning-subtle text-warning border-0 rounded-3 p-3 fs-7 mb-4 d-flex gap-2">
                                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                                        <div>
                                            <p class="fw-bold mb-1">Bank account not configured</p>
                                            <p class="mb-0">Please set up your bank account details in your profile before requesting a withdrawal.</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Bank Name -->
                                <div class="col-12 col-md-6">
                                    <label for="withdrawal_method" class="form-label fw-medium text-dark">Bank / Method</label>
                                    <select name="withdrawal_method" id="withdrawal_method" class="form-select bg-light border-light @error('withdrawal_method') is-invalid @enderror" required>
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
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Account Number -->
                                <div class="col-12 col-md-6">
                                    <label for="account_number" class="form-label fw-medium text-dark">Account Number</label>
                                    <input type="text" name="account_number" id="account_number" 
                                        class="form-control bg-light border-light @error('account_number') is-invalid @enderror" 
                                        value="{{ old('account_number', $bankAccount['account_number'] ?? '') }}" 
                                        placeholder="e.g. 1234567890" required>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Account Name -->
                                <div class="col-12">
                                    <label for="account_name" class="form-label fw-medium text-dark">Account Holder Name</label>
                                    <input type="text" name="account_name" id="account_name" 
                                        class="form-control bg-light border-light @error('account_name') is-invalid @enderror" 
                                        value="{{ old('account_name', $bankAccount['account_name'] ?? '') }}" 
                                        placeholder="Name matching the bank account" required>
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2 text-secondary fs-7">
                                        Transfers will fail if the name doesn't match the account holder.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 glass h-100">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0 text-dark">Actions</h5>
                        </div>
                        <div class="card-body p-4 d-flex flex-column gap-3">
                            <p class="text-secondary fs-7 mb-2">Review your withdrawal details carefully before submitting.</p>
                            
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-medium hover-lift w-100" 
                                {{ ($available_balance ?? 0) < ($min_withdrawal ?? 50000) ? 'disabled' : '' }}>
                                Submit Request
                            </button>
                            
                            <a href="javascript:history.back()" class="btn btn-light border rounded-pill py-2 fw-medium hover-lift w-100">
                                Cancel
                            </a>
                            
                            <div class="mt-4 p-3 bg-light rounded-3 border border-light d-flex gap-3">
                                <i class="bi bi-clock-history text-primary fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">Processing Time</h6>
                                    <p class="fs-7 text-secondary mb-0">Withdrawals are typically processed within 1-2 business days.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
