@extends('layouts.customer')

@section('title', 'WhatsApp API Generator')

@section('breadcrumb')
    <span class="crumb-current">WhatsApp API Generator</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-brands fa-whatsapp" style="color:#25D366;margin-right:10px;"></i>WhatsApp API Generator</h1>
        <p class="page-subtitle">Generate WhatsApp API instance Anda sendiri untuk integrasi ke sistem eksternal, website, atau cURL.</p>
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

<div class="card mb-6">
    <div class="card-header">
        <div class="card-title">Daftar WhatsApp API Instances Anda</div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>Session ID</th>
                        <th>Nomor Terhubung</th>
                        <th>Status Koneksi</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $device)
                        <tr>
                            <td class="font-bold">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #25D366, #128C7E); color: white; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px;">{{ $device->name }}</div>
                                        <div style="font-size: 11px; color: #94A3B8;">Created: {{ $device->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code style="font-size: 12px; color: var(--primary); background: rgba(79, 70, 229, 0.08); padding: 3px 8px; border-radius: 4px;">{{ $device->session_id }}</code>
                            </td>
                            <td>
                                @if($device->phone_number)
                                    <span class="font-semibold" style="color: #334155;">+{{ $device->phone_number }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($device->status === 'connected')
                                    <span class="badge badge-success"><i class="fa-solid fa-check"></i> Connected</span>
                                @elseif($device->status === 'scan_qr' || $device->status === 'connecting')
                                    <span class="badge badge-warning"><i class="fa-solid fa-qrcode"></i> Scan QR Needed</span>
                                @else
                                    <span class="badge badge-danger"><i class="fa-solid fa-circle-exclamation"></i> Disconnected</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('customer.whatsapp-devices.show', $device->uuid) }}" class="btn btn-outline btn-sm">
                                        <i class="fa-solid fa-qrcode"></i> Detail & Scan QR
                                    </a>
                                    <form action="{{ route('customer.whatsapp-devices.destroy', $device->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus WA API Device ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state" style="padding: 40px; text-align: center;">
                                    <div class="empty-state-icon" style="font-size: 40px; margin-bottom: 12px;">📱</div>
                                    <div class="empty-state-title" style="font-size: 16px; font-weight: 700; color: #334155;">Belum Ada WhatsApp API Device</div>
                                    <p style="font-size: 13px; color: #94A3B8; margin-top: 4px;">Klik tombol <strong style="color: var(--primary);">Generate WA API Baru</strong> di atas untuk menghubungkan akun WhatsApp Anda.</p>
                                </div>
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
                <i class="fa-brands fa-whatsapp" style="color: #25D366; margin-right: 6px;"></i> Generate WA API Device
            </h3>
            <button type="button" onclick="document.getElementById('modalCreate').style.display='none'" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #64748B;">&times;</button>
        </div>
        <form action="{{ route('customer.whatsapp-devices.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Nama Device / Label *</label>
                <input type="text" name="name" required placeholder="Contoh: Toko Saya / CS Customer" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Webhook URL (Opsional)</label>
                <input type="url" name="webhook_url" placeholder="https://tokoanda.com/api/wa/incoming" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                <small style="color: #94A3B8; font-size: 11px; margin-top: 4px; display: block;">URL untuk menerima callback pesan masuk.</small>
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
