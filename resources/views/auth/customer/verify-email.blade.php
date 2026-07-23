@extends('layouts.guest')
@section('content')
    <div class="auth-layout">
        <div class="auth-right auth-panel" style="grid-column:1/-1;">
            <div class="auth-form-panel text-center">
                <div class="brand-icon mx-auto mb-4" style="margin:0 auto;">C</div>
                <h2 style="font-size:1.7rem;font-weight:800;">{{ __('Verify Your Email') }}</h2>
                <p class="mb-4">
                    {{ __('A verification link has been sent to your email address. Please check your inbox and click the link to activate your account.') }}
                </p>
                <form action="{{ route('customer.verification.send') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline">{{ __('Resend Verification Email') }} <i
                            class="bi bi-envelope"></i></button>
                </form>
                <p class="mt-4" style="font-size: .85rem;">
                    {{ __('If you didn\'t receive the email, please check your spam or junk folder. If it\'s still missing, contact our support team for assistance.') }}
                </p>
            </div>
        </div>
    </div>
@endsection
