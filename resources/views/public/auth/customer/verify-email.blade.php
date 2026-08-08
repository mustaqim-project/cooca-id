@extends('layouts.public')
@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:120px 20px;">
  <div style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:48px;max-width:420px;width:100%;text-align:center;">
    <div style="width:64px;height:64px;background:var(--primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
      <i class="fas fa-envelope" style="font-size:24px;"></i>
    </div>
    <h1 style="font-size:24px;font-weight:800;color:var(--text);margin-bottom:12px;">Verifikasi Email Anda</h1>
    
    @if (session('message'))
        <div style="background:rgba(16, 185, 129, 0.1);color:#10B981;padding:12px;border-radius:10px;margin-bottom:24px;font-size:14px;">
            {{ session('message') }}
        </div>
    @endif

    <p style="color:var(--text-muted);margin-bottom:32px;font-size:15px;line-height:1.5;">
      Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami akan mengirimkan ulang.
    </p>

    <form method="POST" action="{{ route('customer.verification.send') }}">
        @csrf
        <button type="submit" style="width:100%;padding:14px;background:var(--primary);color:white;border:none;border-radius:12px;font-weight:600;font-size:16px;cursor:pointer;margin-bottom:16px;transition:0.3s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            Kirim Ulang Email Verifikasi
        </button>
    </form>

    <form method="POST" action="{{ route('customer.logout') }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:var(--text-muted);font-weight:500;cursor:pointer;text-decoration:underline;">
            Logout
        </button>
    </form>
  </div>
</div>
@endsection
