@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{!! __('Start <span class="text-gradient">Earning</span> Today') !!}</h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('Join the affiliate program and earn up to 20% commission per sale.') }}</p>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Affiliate Registration') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Create your affiliate account.') }} <a href="{{ route('affiliator.login') }}">{{ __('Already registered? →') }}</a></p>

      @if ($errors->any())
      <div class="alert alert-danger"><ul class="mb-0" style="padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
      @endif

      <form action="{{ route('affiliator.register.submit') }}" method="POST">
        @csrf
        <div class="form-group"><label class="form-label">{{ __('Full Name') }}</label><div class="input-icon-wrap"><i class="bi bi-person input-icon"></i><input type="text" name="name" class="form-control" placeholder="Your name" value="{{ old('name') }}" required></div></div>
        <div class="form-group"><label class="form-label">{{ __('Email Address') }}</label><div class="input-icon-wrap"><i class="bi bi-envelope input-icon"></i><input type="email" name="email" class="form-control" placeholder="you@email.com" value="{{ old('email') }}" required></div></div>
        <div class="form-group"><label class="form-label">{{ __('Password') }}</label><div class="input-icon-wrap"><i class="bi bi-lock input-icon"></i><input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required></div></div>
        <div class="form-group"><label class="form-label">{{ __('Confirm Password') }}</label><div class="input-icon-wrap"><i class="bi bi-lock input-icon"></i><input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required></div></div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Register') }} <i class="bi bi-arrow-right"></i></button>
      </form>
      <p class="text-center mt-4">{{ __('Already have an account?') }} <a href="{{ route('affiliator.login') }}" class="fw-bold">{{ __('Log in →') }}</a></p>
    </div>
  </div>
</div>
@endsection