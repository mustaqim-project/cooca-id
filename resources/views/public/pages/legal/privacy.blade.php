@extends('layouts.public')

@section('title', 'Kebijakan Privasi & Perlindungan Data | COOCA.ID')
@section('description', 'Komitmen COOCA.ID dalam menjaga kerahasiaan data dan analitik bisnis Anda agar Anda dapat mengambil keputusan dengan aman dan tenang.')
@section('keywords', 'kebijakan privasi cooca, privacy policy cooca id, perlindungan data bisnis')

@section('content')
<div style="min-height: 100vh; padding: 140px 20px 80px; background: var(--bg);">
    <div style="max-width: 860px; margin: 0 auto;">
        
        {{-- Header & Breadcrumb --}}
        <div style="margin-bottom: 32px;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
                <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Beranda</a>
                <span>/</span>
                <span style="color: var(--primary); font-weight: 600;">Kebijakan Privasi</span>
            </div>
            <h1 style="font-size: 36px; font-weight: 800; color: var(--text); margin-bottom: 8px; line-height: 1.2;">
                {{ $page->title ?? 'Kebijakan Privasi' }}
            </h1>
            <p style="font-size: 14px; color: var(--text-muted);">
                Terakhir Diperbarui: {{ date('d F Y') }} — PT Cooca Digital Indonesia
            </p>
        </div>

        {{-- Legal Content Card --}}
        <div class="legal-card" style="padding: 36px; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; box-shadow: var(--shadow-lg);">
            <div class="legal-content markdown-body" style="font-size: 15px; line-height: 1.8; color: var(--text-muted);">
                {!! $page->content ?? '' !!}
            </div>
        </div>

    </div>
</div>
@endsection
