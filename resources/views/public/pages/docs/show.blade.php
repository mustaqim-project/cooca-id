@extends('layouts.public')

@section('title', $pageTitle . ' — Dokumentasi COOCA.ID')
@section('description', 'Panduan teknis dan operasional COOCA.ID tentang ' . $pageTitle)

@section('content')
<div style="min-height: 100vh; padding: 100px 20px 80px;">
    <div class="lp-container" style="display: flex; gap: 40px; align-items: flex-start; flex-direction: column; @media(min-width: 992px) { flex-direction: row; }">
        
        {{-- Sidebar --}}
        <aside style="width: 100%; flex-shrink: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 24px; position: sticky; top: 100px; @media (min-width: 992px) { width: 300px; }">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-book"></i> Daftar Panduan
            </h3>
            
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                <li>
                    <a href="{{ route('docs') }}" style="display: block; padding: 8px 12px; font-size: 14px; color: var(--text-muted); text-decoration: none; border-radius: 8px; transition: 0.2s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                        <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> Kembali ke Indeks
                    </a>
                </li>
                <hr style="border: none; border-top: 1px solid var(--border); margin: 8px 0;">
                
                @foreach($sidebarDocs as $doc)
                <li>
                    <a href="{{ route('docs.show', $doc['slug']) }}" 
                       style="display: block; padding: 10px 12px; font-size: 14px; color: {{ $currentSlug === $doc['slug'] ? 'var(--primary)' : 'var(--text)' }}; font-weight: {{ $currentSlug === $doc['slug'] ? '700' : '500' }}; text-decoration: none; border-radius: 8px; background: {{ $currentSlug === $doc['slug'] ? 'var(--primary-glow)' : 'transparent' }}; transition: 0.2s;"
                       onmouseover="if('{{ $currentSlug }}' !== '{{ $doc['slug'] }}') this.style.background='var(--bg)'"
                       onmouseout="if('{{ $currentSlug }}' !== '{{ $doc['slug'] }}') this.style.background='transparent'">
                        {{ $doc['title'] }}
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Main Content --}}
        <main style="flex: 1; min-width: 0; width: 100%;">
            <div class="markdown-body" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: clamp(24px, 5vw, 48px); width: 100%;">
                {!! $content !!}
            </div>
        </main>
    </div>
</div>
@endsection
