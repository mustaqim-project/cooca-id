@extends('layouts.public')
@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:120px 20px;">
  <div style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:48px;max-width:420px;width:100%;text-align:center;">
    <h1 style="font-size:28px;font-weight:800;color:var(--text);margin-bottom:8px;">Masuk ke Akun</h1>
    <p style="color:var(--text-muted);margin-bottom:32px;">Selamat datang kembali di COOCA.ID</p>
    <a href="{{ route('home') }}" style="color:var(--primary);">← Kembali ke Beranda</a>
  </div>
</div>
@endsection
