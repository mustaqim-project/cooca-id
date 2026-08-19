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
    <div class="alert alert-success mb-4">
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
                                        <div style="font-size: 14px; font-weight: 700; color: var(--text);">{{ $device->name }}</div>
                                        <div class="text-xs text-muted">Created: {{ $device->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $device->session_id }}</code>
                            </td>
                            <td>
                                @if($device->phone_number)
                                    <span class="font-semibold text-sm" style="color: var(--text);">+{{ $device->phone_number }}</span>
                                @else
                                    <span class="text-muted text-sm">—</span>
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
                                <div class="empty-state">
                                    <div class="empty-state-icon">📱</div>
                                    <div class="empty-state-title">Belum Ada WhatsApp API Device</div>
                                    <p class="empty-state-text">Klik tombol <strong style="color: var(--primary);">Generate WA API Baru</strong> di atas untuk menghubungkan akun WhatsApp Anda.</p>
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
<div id="modalCreate" class="portal-modal-backdrop" style="display: none;">
    <div class="portal-modal">
        <div class="portal-modal-header">
            <h3 class="portal-modal-title">
                <i class="fa-brands fa-whatsapp" style="color: #25D366;"></i> Generate WA API Device
            </h3>
            <button type="button" onclick="document.getElementById('modalCreate').style.display='none'" class="portal-modal-close">&times;</button>
        </div>
        <form action="{{ route('customer.whatsapp-devices.store') }}" method="POST">
            @csrf
            <div class="portal-modal-body" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group mb-0">
                    <label class="form-label">Nama Device / Label <span class="text-danger">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Toko Saya / CS Customer" class="form-input">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Webhook URL (Opsional)</label>
                    <input type="url" name="webhook_url" placeholder="https://tokoanda.com/api/wa/incoming" class="form-input">
                    <small class="form-hint">URL untuk menerima callback pesan masuk.</small>
                </div>
            </div>
            <div class="portal-modal-footer">
                <button type="button" onclick="document.getElementById('modalCreate').style.display='none'" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Buat Device
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
