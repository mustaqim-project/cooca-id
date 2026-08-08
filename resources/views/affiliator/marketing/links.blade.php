@extends('affiliator.layouts.app')

@section('title', 'Marketing Links')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.marketing_materials.index') }}" class="crumb-link">Marketing Materials</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Link Generator</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Custom Link Generator</div>
            <div class="page-subtitle">Build custom affiliate links with UTM tracking parameters.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.marketing_materials.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Materials
            </a>
        </div>
    </div>

    @php
        $aff = auth('affiliator')->user() ?? auth()->user();
        $refCode = $aff?->referral_code ?? '';
        $defaultUrl = url('/register?ref=' . $refCode);
    @endphp

    <div class="card mb-6">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-link" style="color:var(--primary);margin-right:8px;"></i>Your Main
                Referral Link</div>
        </div>
        <div class="card-body">
            <div
                style="display:flex;align-items:center;gap:10px;background:var(--bg);padding:12px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                <i class="fa-solid fa-link" style="color:var(--primary);font-size:16px;"></i>
                <input type="text" readonly value="{{ $defaultUrl }}" id="mainRefUrl"
                    style="border:none;background:none;outline:none;font-size:14px;font-weight:700;color:var(--text);flex:1;">
                <button onclick="copyToClipboard('{{ $defaultUrl }}', 'Main Referral Link')"
                    class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-copy"></i> Copy Link
                </button>
            </div>
        </div>
    </div>

    {{-- All Referral Links --}}
    <div class="card mb-6">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-list" style="color:var(--primary);margin-right:8px;"></i>All
                Referral Links</div>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
            @forelse($links ?? [] as $link)
                <div
                    style="display:flex;align-items:center;gap:10px;background:var(--bg);padding:12px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="flex:1;">
                        <div class="font-semibold text-sm">{{ $link['name'] ?? 'Referral Link' }}</div>
                        <div class="text-xs text-muted">{{ $link['description'] ?? '' }}</div>
                    </div>
                    <input type="text" readonly value="{{ $link['url'] ?? '' }}"
                        style="border:none;background:none;outline:none;font-size:12px;color:var(--text-muted);flex:1;min-width:0;">
                    <button onclick="copyToClipboard('{{ $link['url'] ?? '' }}', '{{ $link['name'] ?? 'Referral Link' }}')"
                        class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
            @empty
                <div class="text-center text-muted" style="padding:16px 0;font-size:13px;">
                    No referral links available.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Social Media Templates --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-share-nodes"
                    style="color:var(--accent);margin-right:8px;"></i>Social Media Templates</div>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
            @forelse($socialMediaTemplates ?? [] as $platform => $template)
                <div
                    style="display:flex;align-items:flex-start;gap:10px;background:var(--bg);padding:12px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div class="s-icon"
                        style="width:32px;height:32px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-brands fa-{{ $platform }}"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="font-semibold text-sm capitalize">{{ $platform }}</div>
                        <div class="text-xs text-muted" style="margin-top:2px;">{{ $template }}</div>
                    </div>
                    <button onclick="copyToClipboard('{{ addslashes($template) }}', '{{ ucfirst($platform) }} Template')"
                        class="btn btn-outline btn-sm" style="flex-shrink:0;">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
            @empty
                <div class="text-center text-muted" style="padding:16px 0;font-size:13px;">
                    No social media templates available.
                </div>
            @endforelse
        </div>
    </div>
@endsection
