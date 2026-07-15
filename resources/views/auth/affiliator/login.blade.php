@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{{ __('Affiliator') }} <span class="text-gradient">{{ __('Portal') }}</span></h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('Secure access to your affiliator dashboard.') }}</p>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Affiliator Login') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Sign in to manage your affiliator account.') }}</p>

      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('affiliator.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">{{ __('Email Address') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@company.com" required autocomplete="email">
          </div>
        </div>
        <div class="form-group">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <label class="form-label">{{ __('Password') }}</label>
            <a href="{{ route('affiliator.password.request') }}" style="font-size:.82rem;">{{ __('Forgot password?') }}</a>
          </div>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Your password" required autocomplete="current-password">
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Log In') }} <i class="bi bi-arrow-right"></i></button>
      </form>
    </div>
  </div>
</div>
@endsection