@extends('layouts.public')

@section('title', 'Reset Password — COOCA.ID')

@section('content')
<div class="aurora-bg" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 120px 20px 60px;">
    <div style="width: 100%; max-width: 420px;" class="reveal">
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: var(--shadow-xl);">
            <div style="text-align: center; margin-bottom: 28px;">
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 4px;">Buat Password Baru</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Masukkan password baru akun Anda di bawah ini.</p>
            </div>

            @if ($errors->any())
            <div style="padding: 12px 16px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 12px; color: #ef4444; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('customer.password.reset', ['token' => $token ?? '']) }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="reset-email">Email</label>
                    <input id="reset-email" type="email" name="email" value="{{ $email ?? old('email') }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="reset-pass">Password Baru *</label>
                    <div style="position: relative;">
                        <input id="reset-pass" type="password" name="password" required placeholder="••••••••"
                            style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                        <button type="button" onclick="let inp = document.getElementById('reset-pass'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="reset-pass-conf">Konfirmasi Password Baru *</label>
                    <div style="position: relative;">
                        <input id="reset-pass-conf" type="password" name="password_confirmation" required placeholder="••••••••"
                            style="width: 100%; padding: 12px 16px; padding-right: 44px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                        <button type="button" onclick="let inp = document.getElementById('reset-pass-conf'); let icon = this.querySelector('i'); if(inp.type === 'password') { inp.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); } else { inp.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; border-radius: 12px;">
                    Reset Password →
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
