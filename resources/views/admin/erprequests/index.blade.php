@extends('layouts.admin')

@section('title', 'Pengajuan ERP SaaS — COOCA.ID Admin')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <div>
        <div class="breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748B; margin-bottom: 8px;">
            <a href="{{ route('admin.dashboard') }}" style="color: #4F46E5; text-decoration: none;">Admin</a>
            <span>/</span>
            <span>Pengajuan ERP</span>
        </div>
        <h1 style="font-size: 24px; font-weight: 800; color: #1E293B; margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-server text-primary"></i> Pengajuan Instansi & Lisensi ERP
        </h1>
        <p style="color: #64748B; margin: 4px 0 0; font-size: 14px;">
            Kelola permohonan deploy instansi ERP, persetujuan domain, dan aktivasi lisensi SaaS customer.
        </p>
    </div>
</div>

@if(session('success'))
    <div style="background: #DEF7EC; border: 1px solid #31C48D; color: #03543F; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="card" style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); overflow: hidden;">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-size: 12px; text-transform: uppercase; color: #64748B; letter-spacing: 0.5px;">
                        <th style="padding: 14px 18px;">Pemohon (Customer)</th>
                        <th style="padding: 14px 18px;">Produk ERP</th>
                        <th style="padding: 14px 18px;">Domain & Subdomain</th>
                        <th style="padding: 14px 18px;">Status</th>
                        <th style="padding: 14px 18px;">Waktu Pengajuan</th>
                        <th style="padding: 14px 18px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 13px;">
                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 700; color: #1E293B;">{{ $req->customer->name ?? 'N/A' }}</div>
                                <div style="font-size: 12px; color: #64748B;">{{ $req->customer->email ?? '' }}</div>
                                @if($req->customer->phone ?? false)
                                    <div style="font-size: 11px; color: #059669; font-weight: 600; margin-top: 2px;">
                                        <i class="fa-brands fa-whatsapp"></i> +{{ $req->customer->phone }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 18px;">
                                <span style="font-weight: 700; color: #4F46E5;">{{ $req->product->name ?? 'Paket ERP' }}</span>
                            </td>
                            <td style="padding: 14px 18px;">
                                @if($req->requested_subdomain)
                                    <code style="background: #EEF2FF; color: #4F46E5; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 12px;">
                                        https://{{ $req->requested_subdomain }}.cooca.id
                                    </code>
                                @elseif($req->requested_domain)
                                    <code style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 12px;">
                                        https://{{ $req->requested_domain }}
                                    </code>
                                @else
                                    <span style="color: #94A3B8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 14px 18px;">
                                @php
                                    $statusBg = match($req->status) {
                                        'active_trial', 'waiting_setup' => 'background: #DEF7EC; color: #03543F;',
                                        'submitted', 'waiting_approval' => 'background: #FEF3C7; color: #92400E;',
                                        'in_setup', 'domain_setup', 'testing' => 'background: #E0F2FE; color: #075985;',
                                        'rejected', 'trial_expired' => 'background: #FEE2E2; color: #991B1B;',
                                        default => 'background: #F1F5F9; color: #64748B;',
                                    };
                                    $labels = \App\Models\ErpRequest::getStatusLabels();
                                @endphp
                                <span style="font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 12px; {{ $statusBg }}">
                                    {{ $labels[$req->status] ?? strtoupper($req->status) }}
                                </span>
                            </td>
                            <td style="padding: 14px 18px; color: #64748B; font-size: 12px;">
                                {{ $req->created_at ? $req->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td style="padding: 14px 18px; text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('admin.erp-requests.show', $req->id) }}" class="btn btn-sm btn-outline-primary" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 8px;">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #94A3B8; padding: 40px; font-size: 14px;">
                                Belum ada pengajuan instansi ERP.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div style="padding: 16px; border-top: 1px solid #E2E8F0;">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
