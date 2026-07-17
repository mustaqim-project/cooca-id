@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{!! __('Secure <span class="text-gradient">Customer</span> Recovery') !!}</h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('We'll send a reset link to your registered email.') }}</p>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Reset Password') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Enter your email to receive a password reset link.') }}</p>

      @if (session('success'))
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
      @endif
      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('customer.password.request') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">{{ __('Email Address') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="you@company.com" value="{{ old('email') }}" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Send Reset Link') }} <i class="bi bi-send"></i></button>
      </form>
      <p class="text-center mt-4"><a href="{{ route('customer.login') }}"><i class="bi bi-arrow-left"></i> {{ __('Back to Login') }}</a></p>
    </div>
  </div>
</div>
@endsection