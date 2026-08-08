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
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Promotional Banners</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Embeddable HTML banners for your website, blog, or email newsletters.</p>
    </div>
    <a href="{{ route('affiliator.marketing_materials.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Materials
    </a>
</div>

<div class="portal-card mb-6">
    <div class="portal-card-header">
        <div class="portal-card-title">
            <i class="fa-solid fa-images" style="color:var(--primary);"></i>
            Available Banners
        </div>
    </div>

    <div class="portal-card-body p-0">
        <div class="table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th class="portal-th">Banner Name</th>
                        <th class="portal-th">Dimensions</th>
                        <th class="portal-th text-center">Status</th>
                        <th class="portal-th text-right">HTML Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners ?? [] as $banner)
                        <tr>
                            <td class="portal-td font-medium">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="s-icon" style="width:32px;height:32px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                    <span style="font-weight:700;">{{ $banner['name'] ?? 'Banner' }}</span>
                                </div>
                            </td>
                            <td class="portal-td text-muted">
                                <i class="fa-solid fa-vector-square" style="margin-right:4px;"></i> {{ $banner['size'] ?? 'Responsive' }}
                            </td>
                            <td class="portal-td text-center">
                                <span class="badge-status status-active">Ready</span>
                            </td>
                            <td class="portal-td text-right">
                                <button onclick="copyToClipboard('{{ addslashes($banner['html_code'] ?? '') }}', 'Banner HTML Code')" class="btn btn-s btn-sm" style="padding:4px 12px;font-size:11px;">
                                    <i class="fa-solid fa-code"></i> Copy HTML
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="portal-td text-center py-6 text-muted">
                                <i class="fa-solid fa-images" style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
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
