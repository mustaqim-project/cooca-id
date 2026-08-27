@extends('layouts.public')

@section('title', 'Admin Portal Login — COOCA.ID')

@section('content')
<div class="aurora-bg auth-page-wrapper">
    <div class="lp-container">
        <div class="auth-layout-grid">
            
            @include('public.components.auth-left')

            {{-- Right Side: Form --}}
            <div class="auth-right reveal reveal-delay-1" style="width: 100%; max-width: 440px; margin: 0 auto;">
                <div class="auth-card">
            <div style="text-align: center; margin-bottom: 28px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 16px;">
                    @php
                        $siteName = setting('site.name', 'COOCA.ID');
                        $logoLight = setting('site.logo_light') ? asset(setting('site.logo_light')) : (setting('site.logo') ? asset(setting('site.logo')) : null);
                        $logoDark = setting('site.logo_dark') ? asset(setting('site.logo_dark')) : (setting('site.logo') ? asset(setting('site.logo')) : null);
                    @endphp
                    @if($logoLight)
                        <img src="{{ $logoLight }}" alt="{{ $siteName }}" class="logo-light-only" style="height: 36px; width: auto; object-fit: contain;">
                    @endif
                    @if($logoDark)
                        <img src="{{ $logoDark }}" alt="{{ $siteName }}" class="logo-dark-only" style="height: 36px; width: auto; object-fit: contain;">
                    @endif
                    @if(!$logoLight && !$logoDark)
                        <svg width="32" height="32" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="28" height="28" rx="8" fill="url(#login_logo_grad)"/>
                            <path d="M10.5 14C10.5 12.067 12.067 10.5 14 10.5C15.933 10.5 17.5 12.067 17.5 14C17.5 15.933 15.933 17.5 14 17.5C12.067 17.5 10.5 15.933 10.5 14Z" fill="white"/>
                            <defs><linearGradient id="login_logo_grad" x1="0" y1="0" x2="28" y2="28" gradientUnits="userSpaceOnUse"><stop stop-color="#4F46E5"/><stop offset="1" stop-color="#06B6D4"/></linearGradient></defs>
                        </svg>
                        <span>{{ $siteName }}</span>
                    @endif
                </a>
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 4px;">Admin Console</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Masuk ke sistem kontrol internal COOCA.ID</p>
            </div>

            @if ($errors->any())
            <div style="padding: 12px 16px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 12px; color: #ef4444; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">

                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="admin-email">Email Admin</label>
                    <input id="admin-email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@cooca.id"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="admin-pass">Password</label>
                    <div style="position: relative;">
                        <input id="admin-pass" type="password" name="password" required placeholder="••••••••"
                            style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                        <button type="button" onclick="let inp = document.getElementById('admin-pass'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Security Captcha Challenge --}}
                <div class="captcha-card-widget" style="margin-bottom: 20px; padding: 14px; background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 12px; box-sizing: border-box;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 6px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 6px; white-space: nowrap;">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i> Verifikasi Keamanan Captcha
                        </label>
                        <button type="button" onclick="refreshCaptchaWidget(this)" style="background: none; border: none; color: var(--primary); font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; padding: 2px 4px; border-radius: 4px;" title="Ganti Pertanyaan">
                            <i class="fa-solid fa-arrows-rotate"></i> Acak Ulang
                        </button>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <div class="captcha-question-box" style="flex: 0 0 auto; min-width: 110px; padding: 10px 14px; background: var(--surface); border: 1px dashed var(--primary); border-radius: 8px; font-family: monospace; font-size: 16px; font-weight: 800; color: var(--primary); letter-spacing: 2px; text-align: center; user-select: none; box-sizing: border-box;">
                            {{ \App\Helpers\CaptchaHelper::getQuestion() }}
                        </div>
                        <input type="text" name="captcha" required placeholder="Jawaban..." style="flex: 1 1 120px; min-width: 100px; width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit; box-sizing: border-box;">
                    </div>
                </div>

                <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; border-radius: 12px; background: linear-gradient(135deg, #1E293B, #0F172A);">
                    Masuk ke Admin Console →
                </button>
            </form>

            <script>
            function refreshCaptchaWidget(btn) {
                var box = btn.closest('.auth-card').querySelector('.captcha-question-box');
                var icon = btn.querySelector('i');
                if (icon) icon.classList.add('fa-spin');
                fetch("{{ route('captcha.refresh') }}")
                    .then(function(r) { return r.json(); })
                    .then(function(d) {
                        if (box && d.question) box.textContent = d.question;
                        if (icon) icon.classList.remove('fa-spin');
                    })
                    .catch(function() {
                        if (icon) icon.classList.remove('fa-spin');
                    });
            }
            </script>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
