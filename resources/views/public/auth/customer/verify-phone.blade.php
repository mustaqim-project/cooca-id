@extends('layouts.public')
@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:120px 20px;">
  <div style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:48px;max-width:420px;width:100%;text-align:center;">
    <div style="width:64px;height:64px;background:var(--primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
      <i class="fab fa-whatsapp" style="font-size:32px;"></i>
    </div>
    
    @if ($needsPhone)
        <h1 style="font-size:24px;font-weight:800;color:var(--text);margin-bottom:12px;">Lengkapi Nomor WhatsApp</h1>
        <p style="color:var(--text-muted);margin-bottom:32px;font-size:15px;line-height:1.5;">
            Anda mendaftar menggunakan Google. Silakan masukkan nomor WhatsApp Anda agar kami dapat mengirimkan kode OTP untuk verifikasi keamanan.
        </p>

        @if(session('status'))
            <div style="background:rgba(16, 185, 129, 0.1);color:#10B981;padding:12px;border-radius:10px;margin-bottom:24px;font-size:14px;text-align:left;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.otp.update_phone') }}">
            @csrf
            <div style="margin-bottom:24px;text-align:left;">
                <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:var(--text);">Nomor WhatsApp</label>
                <input type="text" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" required
                       style="width:100%;padding:14px;border:1px solid var(--border);border-radius:12px;font-size:15px;background:var(--bg);color:var(--text);transition:0.3s;box-sizing:border-box;">
                @error('phone')
                    <span style="color:#EF4444;font-size:13px;margin-top:8px;display:block;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" style="width:100%;padding:14px;background:var(--primary);color:white;border:none;border-radius:12px;font-weight:600;font-size:16px;cursor:pointer;margin-bottom:16px;transition:0.3s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Kirim Kode OTP
            </button>
        </form>

    @else
        <h1 style="font-size:24px;font-weight:800;color:var(--text);margin-bottom:12px;">Verifikasi WhatsApp</h1>
        <p style="color:var(--text-muted);margin-bottom:32px;font-size:15px;line-height:1.5;">
            Masukkan 6 digit kode OTP yang telah kami kirimkan ke nomor WhatsApp <b>{{ Str::mask(auth('customer')->user()->phone, '*', 3, -3) }}</b>
        </p>

        @if(session('status'))
            <div style="background:rgba(16, 185, 129, 0.1);color:#10B981;padding:12px;border-radius:10px;margin-bottom:24px;font-size:14px;text-align:left;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.otp.verify') }}">
            @csrf
            <div style="margin-bottom:24px;text-align:left;">
                <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:var(--text);">Kode OTP</label>
                <input type="text" name="otp" maxlength="6" placeholder="Masukkan 6 Digit OTP" required
                       style="width:100%;padding:14px;border:1px solid var(--border);border-radius:12px;font-size:20px;text-align:center;letter-spacing:4px;background:var(--bg);color:var(--text);transition:0.3s;box-sizing:border-box;">
                @error('otp')
                    <span style="color:#EF4444;font-size:13px;margin-top:8px;display:block;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" style="width:100%;padding:14px;background:var(--primary);color:white;border:none;border-radius:12px;font-weight:600;font-size:16px;cursor:pointer;margin-bottom:16px;transition:0.3s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Verifikasi OTP
            </button>
        </form>

        <p style="margin-bottom: 16px; font-size: 14px; text-align: center; color: var(--text-muted);">
            Salah nomor WhatsApp? 
            <button type="button" onclick="document.getElementById('change-phone-form').style.display='block';" style="background:none; border:none; color:var(--primary); font-weight:600; cursor:pointer; text-decoration:underline; padding:0;">
                Ganti Nomor
            </button>
        </p>

        <div id="change-phone-form" style="display:none; margin-bottom: 24px; padding-top: 24px; border-top: 1px dashed var(--border); text-align:left;">
            <h3 style="font-size:16px; font-weight:700; color:var(--text); margin-bottom:12px;">Ganti Nomor WhatsApp</h3>
            <form method="POST" action="{{ route('customer.otp.update_phone') }}">
                @csrf
                <div style="margin-bottom:16px;">
                    <input type="text" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone', auth('customer')->user()->phone) }}" required
                           style="width:100%;padding:14px;border:1px solid var(--border);border-radius:12px;font-size:15px;background:var(--bg);color:var(--text);transition:0.3s;box-sizing:border-box;">
                </div>
                <button type="submit" style="width:100%;padding:14px;background:var(--primary);color:white;border:none;border-radius:12px;font-weight:600;font-size:16px;cursor:pointer;transition:0.3s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Update Nomor & Kirim Ulang OTP
                </button>
            </form>
        </div>

        <form method="POST" action="{{ route('customer.otp.resend') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--primary);font-weight:600;font-size:14px;cursor:pointer;text-decoration:underline;">
                Kirim Ulang Kode
            </button>
        </form>
    @endif

    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
        <form method="POST" action="{{ route('customer.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--text-muted);font-weight:500;cursor:pointer;text-decoration:underline;">
                Logout
            </button>
        </form>
    </div>
  </div>
</div>
@endsection
