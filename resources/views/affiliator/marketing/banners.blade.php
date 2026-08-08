@extends('affiliator.layouts.app')

@section('title', 'Marketing Banners')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.marketing_materials.index') }}" class="crumb-link">Marketing Materials</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Banners</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Promotional Banners</div>
            <div class="page-subtitle">Embeddable HTML banners for your website, blog, or email newsletters.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.marketing_materials.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Materials
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-images"
                    style="color:var(--primary);margin-right:8px;"></i>Available Banners</div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Banner Name</th>
                            <th>Dimensions</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">HTML Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners ?? [] as $banner)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="s-icon"
                                            style="width:32px;height:32px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                        <span class="font-bold text-sm">{{ $banner['name'] ?? 'Banner' }}</span>
                                    </div>
                                </td>
                                <td class="text-muted text-sm">
                                    <i class="fa-solid fa-vector-square" style="margin-right:4px;"></i>
                                    {{ $banner['size'] ?? 'Responsive' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">Ready</span>
                                </td>
                                <td class="text-right">
                                    <button
                                        onclick="copyToClipboard('{{ addslashes($banner['html_code'] ?? '') }}', 'Banner HTML Code')"
                                        class="btn btn-outline btn-sm" style="padding:4px 12px;font-size:11px;">
                                        <i class="fa-solid fa-code"></i> Copy HTML
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-images"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    No promotional banners available at the moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
