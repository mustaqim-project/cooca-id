@extends('layouts.admin')

@section('title', 'Landing Page Builder CMS — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Landing CMS</span>
        </div>
        <h1 class="page-title">Landing Page Content Manager</h1>
        <p class="page-subtitle">Customize Hero headlines, subtexts, feature badges, trust logos, and CTA buttons.</p>
    </div>
</div>

<form action="{{ route('admin.cms.landing.update') }}" method="POST">
    @csrf
    <div class="card" style="max-width: 800px;">
        <div class="card-header"><div class="card-title">Hero Section Config</div></div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Hero Title Headline</label>
                <input type="text" name="landing_hero_title" class="form-input" value="{{ $landing['hero_title'] ?? 'Platform ERP SaaS Terlengkap untuk Skala Bisnis Modern' }}">
            </div>
            <div class="form-group">
                <label class="form-label">Hero Subtitle</label>
                <textarea name="landing_hero_subtitle" class="form-textarea" rows="3">{{ $landing['hero_subtitle'] ?? 'Kelola POS, Akuntansi, Stok, dan Multi-Cabang dalam satu platform enterprise.' }}</textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">CTA Button Label</label>
                    <input type="text" name="landing_hero_cta_text" class="form-input" value="{{ $landing['hero_cta_text'] ?? 'Coba Gratis 14 Hari' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">CTA Button Link</label>
                    <input type="text" name="landing_hero_cta_link" class="form-input" value="{{ $landing['hero_cta_link'] ?? '/customer/register' }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">🎨 Save Landing Content</button>
        </div>
    </div>
</form>
@endsection
