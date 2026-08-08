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
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Custom Link Generator</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Build custom affiliate links with UTM tracking parameters.</p>
    </div>
    <a href="{{ route('affiliator.marketing_materials.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Materials
    </a>
</div>

@php
    $aff = auth('affiliator')->user() ?? auth()->user();
    $refCode = $aff?->referral_code ?? '';
    $defaultUrl = url('/register?ref=' . $refCode);
@endphp

<div class="portal-card mb-6">
    <div class="portal-card-header">
        <div class="portal-card-title">
            <i class="fa-solid fa-link" style="color:var(--primary);"></i>
            Your Main Referral Link
        </div>
    </div>
    <div class="portal-card-body">
        <div style="display:flex;align-items:center;gap:10px;background:var(--bg);padding:12px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
            <i class="fa-solid fa-link" style="color:var(--primary);font-size:16px;"></i>
            <input type="text" readonly value="{{ $defaultUrl }}" id="mainRefUrl" style="border:none;background:none;outline:none;font-size:14px;font-weight:700;color:var(--text);flex:1;">
            <button onclick="copyToClipboard('{{ $defaultUrl }}', 'Main Referral Link')" class="btn btn-p btn-sm">
                <i class="fa-solid fa-copy"></i> Copy Link
            </button>
        </div>
    </div>
</div>
@endsection
