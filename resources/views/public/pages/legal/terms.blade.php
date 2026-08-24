@extends('layouts.public')

@section('title', 'Syarat & Ketentuan Layanan | COOCA.ID')
@section('description', 'Ketentuan lisensi dan aturan penggunaan platform COOCA.ID. Kerangka kerja transparan untuk mendukung pertumbuhan bisnis Anda.')
@section('keywords', 'syarat dan ketentuan cooca, terms of service cooca id, ketentuan lisensi software')

@section('content')
<div style="min-height:100vh;padding:120px 20px 80px;">
  <div style="max-width:800px;margin:0 auto;">
    <h1 style="font-size:40px;font-weight:800;color:var(--text);margin-bottom:24px;">{{ $page->title }}</h1>
    <div class="legal-content" style="font-size:16px;line-height:1.8;color:var(--text-muted);">{!! $page->content ?? '' !!}</div>
  </div>
</div>
@endsection
