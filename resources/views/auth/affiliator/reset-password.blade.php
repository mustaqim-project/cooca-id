@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{!! __('Create <span class="text-gradient">New Password</span>') !!}</h2>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Set New Password') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Choose a strong password for your account.') }}</p>

      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('affiliator.password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
        <div class="form-group">
          <label class="form-label">{{ __('New Password') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">{{ __('Confirm Password') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Reset Password') }} <i class="bi bi-shield-check"></i></button>
      </form>
      <p class="text-center mt-4"><a href="{{ route('affiliator.login') }}"><i class="bi bi-arrow-left"></i> {{ __('Back to Login') }}</a></p>
    </div>
  </div>
</div>
@endsection