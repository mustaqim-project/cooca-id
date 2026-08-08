@extends('layouts.admin')

@section('title', 'Detail Pengajuan ERP — COOCA.ID Admin')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <div>
        <div class="breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748B; margin-bottom: 8px;">
            <a href="{{ route('admin.dashboard') }}" style="color: #4F46E5; text-decoration: none;">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.erp-requests.index') }}" style="color: #4F46E5; text-decoration: none;">Pengajuan ERP</a>
            <span>/</span>
            <span>Detail #{{ substr($request->id, 0, 8) }}</span>
        </div>
        <h1 style="font-size: 24px; font-weight: 800; color: #1E293B; margin: 0;">
            Detail Pengajuan ERP — {{ $request->customer->name ?? 'Customer' }}
        </h1>
    </div>
</div>

@if(session('success'))
    <div style="background: #DEF7EC; border: 1px solid #31C48D; color: #03543F; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    {{-- Left Main Detail --}}
    <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <h3 style="font-size: 16px; font-weight: 800; color: #1E293B; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
            Informasi Instansi & Pemohon
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px;">
            <div>
                <div style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Nama Customer</div>
                <div style="font-weight: 700; color: #1E293B; margin-top: 4px;">{{ $request->customer->name ?? '-' }}</div>
            </div>
            <div>
                <div style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Email Customer</div>
                <div style="font-weight: 700; color: #1E293B; margin-top: 4px;">{{ $request->customer->email ?? '-' }}</div>
            </div>
            <div>
                <div style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Produk ERP</div>
                <div style="font-weight: 700; color: #4F46E5; margin-top: 4px;">{{ $request->product->name ?? '-' }}</div>
            </div>
            <div>
                <div style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Nomor WA</div>
                <div style="font-weight: 700; color: #059669; margin-top: 4px;">+{{ $request->customer->phone ?? '-' }}</div>
            </div>
            <div>
                <div style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Subdomain Yang Diminta</div>
                <div style="font-weight: 700; color: #1E293B; margin-top: 4px;">
                    {{ $request->requested_subdomain ? $request->requested_subdomain . '.cooca.id' : '-' }}
                </div>
            </div>
            <div>
                <div style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Custom Domain</div>
                <div style="font-weight: 700; color: #1E293B; margin-top: 4px;">
                    {{ $request->requested_domain ?? '-' }}
                </div>
            </div>
        </div>

        @if($request->notes)
            <div style="margin-top: 20px; padding: 12px; background: #F8FAFC; border-radius: 10px; border: 1px solid #E2E8F0;">
                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px;">Catatan Pemohon:</div>
                <div style="font-size: 13px; color: #334155;">{{ $request->notes }}</div>
            </div>
        @endif

        @if($request->admin_notes)
            <div style="margin-top: 12px; padding: 12px; background: #FEF3C7; border-radius: 10px; border: 1px solid #FCD34D;">
                <div style="font-size: 11px; font-weight: 700; color: #92400E; text-transform: uppercase; margin-bottom: 4px;">Catatan Admin:</div>
                <div style="font-size: 13px; color: #78350F;">{{ $request->admin_notes }}</div>
            </div>
        @endif
    </div>

    {{-- Right Actions & Status Panel --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <h4 style="font-size: 14px; font-weight: 800; color: #1E293B; margin-bottom: 12px;">Status Pengajuan</h4>
            
            @php
                $labels = \App\Models\ErpRequest::getStatusLabels();
            @endphp
            <div style="font-size: 16px; font-weight: 800; color: #4F46E5; margin-bottom: 16px;">
                {{ $labels[$request->status] ?? strtoupper($request->status) }}
            </div>

            @if($request->approvedBy)
                <div style="font-size: 12px; color: #64748B; margin-bottom: 16px;">
                    Disetujui oleh: <strong>{{ $request->approvedBy->name ?? 'Admin' }}</strong><br>
                    Waktu: {{ $request->approved_at ? $request->approved_at->translatedFormat('d M Y, H:i') : '-' }}
                </div>
            @endif

            {{-- Action Buttons --}}
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @if(in_array($request->status, ['submitted', 'waiting_approval']))
                    <form action="{{ route('admin.erp-requests.approve', $request->id) }}" method="POST" style="background: #F8FAFC; padding: 14px; border-radius: 10px; border: 1px solid #E2E8F0;">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <label for="admin_notes" style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 6px; text-transform: uppercase;">Catatan Persetujuan (Wajib)</label>
                            <textarea name="admin_notes" id="admin_notes" rows="3" required style="width: 100%; border: 1px solid #CBD5E1; border-radius: 6px; padding: 8px; font-size: 12px; font-family: inherit; resize: vertical;" placeholder="Tulis catatan persetujuan untuk dikirim ke email customer..."></textarea>
                        </div>
                        <button type="submit" style="width: 100%; background: #10B981; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-check"></i> Setujui & Aktifkan ERP
                        </button>
                    </form>

                    <form action="{{ route('admin.erp-requests.reject', $request->id) }}" method="POST" style="margin-top: 6px;">
                        @csrf
                        <input type="hidden" name="rejection_reason" value="Tidak memenuhi syarat deployment">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')" style="width: 100%; background: #EF4444; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-xmark"></i> Tolak Pengajuan
                        </button>
                    </form>
                @endif

                @if($request->status === 'waiting_setup')
                    <form action="{{ route('admin.erp-requests.mark-in-setup', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; background: #0284C7; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-gears"></i> Mulai Proses Setup Instansi
                        </button>
                    </form>
                @endif

                @if($request->status === 'in_setup')
                    <form action="{{ route('admin.erp-requests.mark-domain-setup', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; background: #0284C7; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-globe"></i> Konfigurasi Domain
                        </button>
                    </form>
                @endif

                @if($request->status === 'domain_setup')
                    <form action="{{ route('admin.erp-requests.mark-testing', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; background: #0284C7; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-vial"></i> Pengujian / Testing
                        </button>
                    </form>
                @endif

                @if($request->status === 'testing')
                    <form action="{{ route('admin.erp-requests.confirm-ready', $request->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="trial_days" value="14">
                        <button type="submit" style="width: 100%; background: #6366F1; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-rocket"></i> Aktivasi Trial & Terbitkan Lisensi
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if($request->license)
            <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <h4 style="font-size: 14px; font-weight: 800; color: #1E293B; margin-bottom: 12px; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
                    <i class="fa-solid fa-key" style="color: #4F46E5; margin-right: 6px;"></i> Informasi Lisensi ERP
                </h4>
                <div style="display: grid; grid-template-columns: 1fr; gap: 12px; font-size: 13px;">
                    <div>
                        <span style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">License Code:</span>
                        <code style="font-family: monospace; background: #F1F5F9; padding: 4px 8px; border-radius: 4px; display: block; margin-top: 2px; font-weight: bold; color: #0F172A; font-size: 12px;">{{ $request->license->license_code }}</code>
                    </div>
                    <div>
                        <span style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Token Code:</span>
                        <code style="font-family: monospace; background: #F1F5F9; padding: 4px 8px; border-radius: 4px; display: block; margin-top: 2px; font-weight: bold; color: #0F172A; font-size: 12px;">{{ $request->license->token_code }}</code>
                    </div>
                    <div>
                        <span style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Domain Terdaftar:</span>
                        <div style="font-weight: 700; color: #1E293B; margin-top: 2px;">
                            <a href="https://{{ $request->license->domain }}" target="_blank" style="color: #4F46E5; text-decoration: none;">
                                {{ $request->license->domain }} <i class="fa-solid fa-up-right-from-square" style="font-size: 10px;"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <span style="color: #64748B; font-weight: 600; font-size: 11px; text-transform: uppercase;">Masa Berlaku:</span>
                        <div style="font-weight: 700; color: #1E293B; margin-top: 2px;">
                            {{ $request->license->starts_at?->format('d M Y') ?? '—' }} s/d {{ $request->license->expires_at?->format('d M Y') ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
