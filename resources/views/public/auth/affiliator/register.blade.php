@extends('layouts.public')

@section('title', 'Daftar Partner Affiliasi — COOCA.ID')

@section('content')
<div class="aurora-bg auth-page-wrapper">
    <div class="lp-container">
        <div class="auth-layout-grid">
            
            @include('public.components.auth-left')

            {{-- Right Side: Form --}}
            <div class="auth-right reveal reveal-delay-1" style="width: 100%; max-width: 480px; margin: 0 auto;">
                <div class="auth-card">
            <div style="text-align: center; margin-bottom: 28px;">
                <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #7C3AED, var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; margin: 0 auto 16px; border: 1px solid var(--border);"><i class="fa-solid fa-rocket"></i></div>
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 4px;">Daftar Partner Affiliasi</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Dapatkan komisi hingga 30% dari setiap penjualan</p>
            </div>

            @if ($errors->any())
            <div style="padding: 12px 16px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 12px; color: #ef4444; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('affiliator.register.submit') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="aff-reg-name">Nama Lengkap *</label>
                    <input id="aff-reg-name" type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Anda"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="aff-reg-email">Email *</label>
                    <input id="aff-reg-email" type="email" name="email" value="{{ old('email') }}" required placeholder="partner@email.com"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>



                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="aff-reg-pass">Password *</label>
                        <div style="position: relative;">
                            <input id="aff-reg-pass" type="password" name="password" required placeholder="••••••••"
                                style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                            <button type="button" onclick="let inp = document.getElementById('aff-reg-pass'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="aff-reg-pass-conf">Konfirmasi *</label>
                        <div style="position: relative;">
                            <input id="aff-reg-pass-conf" type="password" name="password_confirmation" required placeholder="••••••••"
                                style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                            <button type="button" onclick="let inp = document.getElementById('aff-reg-pass-conf'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer; font-size: 13px; color: var(--text-muted);">
                        <input type="checkbox" name="terms" required style="margin-top: 4px;">
                        <span>
                            Saya telah membaca dan menyetujui 
                            <a href="{{ route('affiliate.terms') }}" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">Syarat &amp; Ketentuan Partner Affiliasi</a> serta 
                            <a href="{{ route('privacy') }}" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">Kebijakan Privasi</a>.
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; border-radius: 12px;">
                    <i class="fa-solid fa-rocket"></i> Buat Akun Partner
                </button>
            </form>

            <div style="display:flex;align-items:center;margin:24px 0;">
                <hr style="flex-grow:1;border:none;border-top:1px solid var(--border);margin:0;">
                <span style="padding:0 12px;font-size:13px;color:var(--text-muted);font-weight:500;">ATAU</span>
                <hr style="flex-grow:1;border:none;border-top:1px solid var(--border);margin:0;">
            </div>

            <a href="{{ route('affiliator.auth.google') }}" style="display:flex;align-items:center;justify-content:center;width:100%;padding:14px;background:#fff;color:#333;border:1px solid var(--border);border-radius:12px;font-weight:600;font-size:15px;text-decoration:none;transition:0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='#fff'">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width:20px;margin-right:10px;">
                Daftar dengan Google
            </a>

            <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
                Sudah terdaftar? <a href="{{ route('affiliator.login') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">Masuk di sini</a>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
