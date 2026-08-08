@extends('affiliator.layouts.app')

@section('title', 'Marketing Materials')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Marketing Materials</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Marketing Materials & Product Links</div>
            <div class="page-subtitle">Get product referral links and promotional assets to share with your audience.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.marketing_materials.banners') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-image"></i> View Banners
            </a>
            <a href="{{ route('affiliator.marketing_materials.links') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-link"></i> Link Generator
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Referral Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $product)
                            @php
                                $aff = auth('affiliator')->user() ?? auth()->user();
                                $refCode = $aff?->referral_code ?? '';
                                $pId = $product['id'] ?? ($product->id ?? '');
                                $refLink =
                                    $product['referral_link'] ?? url('/register?ref=' . $refCode . '&product=' . $pId);
                            @endphp
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="s-icon"
                                            style="width:32px;height:32px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                        <span
                                            class="font-bold text-sm">{{ $product['name'] ?? ($product->name ?? 'Product') }}</span>
                                    </div>
                                </td>
                                <td class="text-muted text-sm" style="max-width:300px;">
                                    {{ Str::limit($product['description'] ?? ($product->description ?? 'Cloud ERP Module'), 80) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">Active</span>
                                </td>
                                <td class="text-right">
                                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                        <button
                                            onclick="copyToClipboard('{{ $refLink }}', '{{ $product['name'] ?? ($product->name ?? 'Product') }} Link')"
                                            class="btn btn-outline btn-sm" style="padding:4px 10px;font-size:11px;">
                                            <i class="fa-solid fa-copy"></i> Copy Link
                                        </button>
                                        <a href="{{ $refLink }}" target="_blank" class="btn btn-outline btn-sm"
                                            style="padding:4px 8px;font-size:11px;" title="Test Link">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-box-open"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    No promotional products available yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($products ?? [], 'hasPages') && ($products ?? [])->hasPages())
                <div style="padding:16px;border-top:1px solid var(--border);">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
