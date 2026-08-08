@extends('layouts.public')

@section('title', 'Login Portal Partner — COOCA.ID')

@section('content')
<div class="aurora-bg auth-page-wrapper">
    <div class="lp-container">
        <div class="auth-layout-grid">
            
            @include('public.components.auth-left')

            {{-- Right Side: Form --}}
            <div class="auth-right reveal reveal-delay-1" style="width: 100%; max-width: 440px; margin: 0 auto;">
                <div class="auth-card">
            <div style="text-align: center; margin-bottom: 28px;">
                <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #7C3AED, var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; margin: 0 auto 16px; border: 1px solid var(--border);">🤝</div>
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 4px;">Portal Partner COOCA.ID</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Masuk ke dashboard komisi &amp; affiliate Anda</p>
            </div>

            @if ($errors->any())
            <div style="padding: 12px 16px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 12px; color: #ef4444; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('affiliator.login.submit') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="aff-email">Email Partner</label>
                    <input id="aff-email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="partner@email.com"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="aff-pass">Password</label>
                    <div style="position: relative;">
                        <input id="aff-pass" type="password" name="password" required placeholder="••••••••"
                            style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                        <button type="button" onclick="let inp = document.getElementById('aff-pass'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
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

                <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; border-radius: 12px;">
                    Masuk ke Dashboard Partner →
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

            <div style="display:flex;align-items:center;margin:24px 0;">
                <hr style="flex-grow:1;border:none;border-top:1px solid var(--border);margin:0;">
                <span style="padding:0 12px;font-size:13px;color:var(--text-muted);font-weight:500;">ATAU</span>
                <hr style="flex-grow:1;border:none;border-top:1px solid var(--border);margin:0;">
            </div>

            <a href="{{ route('affiliator.auth.google') }}" style="display:flex;align-items:center;justify-content:center;width:100%;padding:14px;background:#fff;color:#333;border:1px solid var(--border);border-radius:12px;font-weight:600;font-size:15px;text-decoration:none;transition:0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='#fff'">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width:20px;margin-right:10px;">
                Masuk dengan Google
            </a>

            <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
                Belum menjadi partner? <a href="{{ route('affiliator.register') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">Daftar Partner Sekarang</a>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
