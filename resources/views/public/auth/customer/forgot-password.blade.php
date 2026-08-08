@extends('layouts.public')

@section('title', 'Lupa Password — COOCA.ID')

@section('content')
<div class="aurora-bg" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 120px 20px 60px;">
    <div style="width: 100%; max-width: 420px;" class="reveal">
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: var(--shadow-xl);">
            <div style="text-align: center; margin-bottom: 28px;">
                <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px; border: 1px solid var(--border);">🔑</div>
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 4px;">Lupa Password?</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk mereset password.</p>
            </div>

            @if (session('success'))
            <div style="padding: 12px 16px; background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.3); border-radius: 12px; color: #22c55e; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div style="padding: 12px 16px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 12px; color: #ef4444; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('customer.password.request') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="forgot-email">Email Terdaftar</label>
                    <input id="forgot-email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@perusahaan.com"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font-size: 14px; outline: none; font-family: inherit;">
                </div>

                <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; border-radius: 12px;">
                    Kirim Link Reset →
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; font-size: 14px;">
                <a href="{{ route('customer.login') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali ke Halaman Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
