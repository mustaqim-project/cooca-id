@extends('layouts.public')

@section('title', 'Masuk ke Akun Pelanggan — COOCA.ID')

@section('content')
<div class="aurora-bg auth-page-wrapper">
    <div class="lp-container">
        <div class="auth-layout-grid">
            
            @include('public.components.auth-left')

            {{-- Right Side: Form --}}
            <div class="auth-right reveal reveal-delay-1" style="width: 100%; max-width: 480px; margin: 0 auto;">
                <div class="auth-card">

            {{-- Top Icon / Brand --}}
            <div style="text-align: center; margin-bottom: 28px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; font-size: 22px; font-weight: 800; color: var(--text);">
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
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text); margin-top: 16px; margin-bottom: 4px;">Selamat Datang Kembali</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Masuk ke portal bisnis Anda</p>
            </div>

            {{-- Validation Errors / Status --}}
            @if ($errors->any())
            <div style="padding: 12px 16px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 12px; color: #ef4444; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 16px;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('status'))
            <div style="padding: 12px 16px; background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.3); border-radius: 12px; color: #22c55e; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
            @endif

            {{-- Google OAuth Button --}}
            <a href="{{ route('customer.auth.google') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 600; color: var(--text); text-decoration: none; margin-bottom: 20px; transition: all .2s;">
                <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Masuk dengan Google
            </a>

            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="flex: 1; height: 1px; background: var(--border);"></div>
                <span style="padding: 0 12px; font-size: 12px; color: var(--text-muted); text-transform: uppercase;">atau</span>
                <div style="flex: 1; height: 1px; background: var(--border);"></div>
            </div>

            {{-- Form --}}
            <form action="{{ route('customer.login.submit') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="customer-email">Email</label>
                    <input id="customer-email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@perusahaan.com"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text);" for="customer-password">Password</label>
                        <a href="{{ route('customer.password.request') }}" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 500;">Lupa password?</a>
                    </div>
                    <div style="position: relative;">
                        <input id="customer-password" type="password" name="password" required placeholder="••••••••"
                            style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                        <button type="button" onclick="let inp = document.getElementById('customer-password'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
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

                <div style="display: flex; align-items: center; margin-bottom: 24px;">
                    <input type="checkbox" name="remember" id="remember" style="margin-right: 8px; accent-color: var(--primary);">
                    <label for="remember" style="font-size: 13px; color: var(--text-muted); cursor: pointer;">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; border-radius: 12px;" id="customer-login-btn">
                    Masuk Sekarang →
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

            <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
                Belum punya akun? <a href="{{ route('customer.register') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">Daftar Gratis 14 Hari</a>
            </div>

        </div>
            </div>
        </div>
    </div>
</div>
@endsection
