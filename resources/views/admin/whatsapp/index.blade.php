@extends('layouts.admin')

@section('title', 'WhatsApp API Generator — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>WhatsApp API Generator</span>
        </div>
        <h1 class="page-title">WhatsApp API Generator</h1>
        <p class="page-subtitle">Kelola gateway WhatsApp API terisolasi, kunci akses integrasi (Fonnte-Style), dan status koneksi per device.</p>
    </div>
    <div class="page-actions">
        <button type="button" onclick="document.getElementById('modalCreate').style.display='flex'" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Generate WA API Baru
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10B981; border-radius: 6px; color: #047857; font-size: 14px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>Session ID</th>
                        <th>Nomor Terhubung</th>
                        <th>Status Koneksi</th>
                        <th>Webhook URL</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $device)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md" style="background: linear-gradient(135deg, #25D366, #128C7E); color: white; font-size: 18px; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px;">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-base">{{ $device->name }}</div>
                                        <div class="text-xs text-muted">Created: {{ $device->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="text-primary font-mono" style="font-size: 12px; background: rgba(79, 70, 229, 0.08); padding: 3px 8px; border-radius: 4px;">{{ $device->session_id }}</code>
                            </td>
                            <td>
                                @if($device->phone_number)
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">+{{ $device->phone_number }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($device->status === 'connected')
                                    <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.15); color: #065F46; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                                        <span class="status-dot active" style="width: 8px; height: 8px; background: #10B981; border-radius: 50%;"></span> Connected
                                    </span>
                                @elseif($device->status === 'scan_qr' || $device->status === 'connecting')
                                    <span class="badge badge-amber" style="background: rgba(245, 158, 11, 0.15); color: #92400E; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                                        <span class="status-dot warning" style="width: 8px; height: 8px; background: #F59E0B; border-radius: 50%;"></span> Scan QR Needed
                                    </span>
                                @else
                                    <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.15); color: #991B1B; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                                        <span class="status-dot inactive" style="width: 8px; height: 8px; background: #EF4444; border-radius: 50%;"></span> Disconnected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($device->webhook_url)
                                    <span class="text-xs text-slate-500 font-mono" title="{{ $device->webhook_url }}">{{ Str::limit($device->webhook_url, 30) }}</span>
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="td-actions" style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('admin.whatsapp-devices.show', $device->uuid) }}" class="btn btn-outline btn-sm">
                                        <i class="fa-solid fa-qrcode"></i> Detail & Scan QR
                                    </a>
                                    <form action="{{ route('admin.whatsapp-devices.destroy', $device->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus device ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Hapus Device">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                Belum ada WhatsApp API device terdaftar. Klik tombol <strong class="text-primary">Generate WA API Baru</strong> di atas untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create Device -->
<div id="modalCreate" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 16px; max-width: 480px; width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid #E2E8F0;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1E293B;">
                <i class="fa-brands fa-whatsapp text-emerald-500" style="color: #25D366; margin-right: 6px;"></i> Generate WA API Device
            </h3>
            <button type="button" onclick="document.getElementById('modalCreate').style.display='none'" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #64748B;">&times;</button>
        </div>
        <form action="{{ route('admin.whatsapp-devices.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Nama Device / Label *</label>
                <input type="text" name="name" required placeholder="Contoh: CS Utama / Notifikasi Billing" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Webhook URL (Opsional)</label>
                <input type="url" name="webhook_url" placeholder="https://domain.com/api/wa/webhook" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                <small style="color: #94A3B8; font-size: 11px; margin-top: 4px; display: block;">URL untuk menerima pesan masuk & update status delivery.</small>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                <button type="button" onclick="document.getElementById('modalCreate').style.display='none'" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Buat Device
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
