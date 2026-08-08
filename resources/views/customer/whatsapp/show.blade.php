@extends('layouts.customer')

@section('title', 'Detail WA Device - ' . $device->name)

@section('breadcrumb')
    <a href="{{ route('customer.whatsapp-devices.index') }}">WhatsApp API</a>
    <span class="crumb-separator">/</span>
    <span class="crumb-current">{{ $device->name }}</span>
@endsection

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-brands fa-whatsapp" style="color:#25D366;margin-right:10px;"></i>{{ $device->name }}</h1>
        <p class="page-subtitle">Session ID: <code style="font-family:monospace;color:var(--primary);">{{ $device->session_id }}</code></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.whatsapp-devices.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ $qrHtmlUrl }}" target="_blank" class="btn btn-primary">
            <i class="fa-solid fa-external-link"></i> Buka HTML QR Viewer ↗
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10B981; border-radius: 6px; color: #047857; font-size: 14px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(239, 68, 68, 0.1); border-left: 4px solid #EF4444; border-radius: 6px; color: #B91C1C; font-size: 14px;">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
    <!-- Left Column: QR Code Card -->
    <div class="card" style="align-self: start;">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-qrcode" style="color:var(--primary);margin-right:6px;"></i> Scan Barcode WhatsApp</div>
        </div>
        <div class="card-body" style="padding: 24px; text-align: center; display: flex; flex-direction: column; align-items: center;">
            <div id="qrCodeContainer" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                @if($device->status === 'connected')
                    <div style="margin: 20px 0; padding: 20px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; width: 100%; box-sizing: border-box;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: #10B981; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px auto;">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div style="font-weight: 700; color: #047857; font-size: 16px;">WhatsApp Terhubung!</div>
                        <div style="font-size: 13px; color: #475569; margin-top: 4px;">Nomor: {{ $device->phone_number ? '+'.$device->phone_number : 'Aktif' }}</div>
                        <p style="font-size: 11px; color: #10B981; margin-top: 8px; font-weight: 600;">Kredensial API & Fitur Uji Coba Telah Aktif di Sebelah Kanan</p>
                    </div>
                @elseif($qrCodeDataUrl || $device->qr_code)
                    <div style="background: white; padding: 12px; border-radius: 12px; border: 1px solid #E2E8F0; margin: 16px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <img id="qrImageElement" src="{{ $device->qr_code ?: $qrCodeDataUrl }}" alt="Scan QR Code" style="width: 220px; height: 220px; display: block;">
                    </div>
                    <p style="font-size: 12px; color: #64748B; margin: 0;">Buka WhatsApp di HP &gt; Perangkat Tertaut &gt; Tautkan Perangkat</p>
                    <p style="font-size: 11px; color: var(--primary); margin-top: 6px; font-weight: 500;">
                        <i class="fa-solid fa-spinner fa-spin" style="margin-right: 4px;"></i> Memantau hasil scan barcode secara realtime...
                    </p>
                @else
                    <div style="margin: 30px 0; color: #94A3B8;">
                        <i class="fa-solid fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 13px;">Memuat Barcode QR...</p>
                    </div>
                @endif
            </div>

            <a href="" class="btn btn-ghost btn-sm" style="margin-top: 16px;">
                <i class="fa-solid fa-rotate-right"></i> Refresh Barcode Manual
            </a>
        </div>
    </div>

    <!-- Right Column: API Credentials, Direct Testing & Interactive Docs (Revealed after Connection) -->
    <div id="connectedFeaturesContainer" style="grid-column: span 2; display: {{ $device->status === 'connected' ? 'flex' : 'none' }}; flex-direction: column; gap: 24px;">
        <!-- API Credentials Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-key" style="color:var(--primary);margin-right:6px;"></i> Kredensial WhatsApp API (Gunakan di ERP / Booking)</div>
            </div>
            <div class="card-body" style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px;">X-WA-API-KEY (Secret API Key Anda)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" readonly value="{{ $device->api_key }}" style="flex: 1; font-family: monospace; font-size: 13px; padding: 10px 12px; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 8px; color: #334155;">
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $device->api_key }}'); alert('API Key berhasil disalin!');" class="btn btn-outline">
                            <i class="fa-solid fa-copy"></i> Salin API Key
                        </button>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px;">API Endpoint Pengiriman Pesan</label>
                    <input type="text" readonly value="{{ url('/api/v1/wa/send') }}" style="width: 100%; font-family: monospace; font-size: 13px; padding: 10px 12px; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 8px; color: #334155; box-sizing: border-box;">
                </div>
            </div>
        </div>

        <!-- Testing Form Card (Live Send Text & Direct File) -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-paper-plane" style="color:var(--primary);margin-right:6px;"></i> Uji Coba Pengiriman Pesan & File Langsung</div>
            </div>
            <div class="card-body" style="padding: 24px;">
                <form id="testSendForm" onsubmit="handleTestSendAjax(event, this)" action="{{ route('customer.whatsapp-devices.test-send', $device->uuid) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">

                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Nomor Tujuan *</label>
                            <input type="text" name="target" required placeholder="08123456789 / 628123456789" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Unggah File Langsung (PDF, Office, Gambar, Video, Audio, Zip / Max 16MB)</label>

                            <input type="file" name="file" onchange="validateFileSize(this)" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 13px; box-sizing: border-box; background: white;">

                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">ATAU Masukkan URL Media (Opsional)</label>
                            <input type="url" name="media_url" placeholder="https://domain.com/storage/invoice-1002.pdf" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Nama File Kustom (Opsional)</label>
                            <input type="text" name="filename" placeholder="Contoh: Invoice_Pembayaran_#BK-902.pdf" style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Isi Pesan / Caption *</label>
                        <textarea name="message" rows="3" placeholder="Tulis isi pesan atau caption file..." style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; resize: vertical;"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pesan / File Uji Coba
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Interactive API Documentation directly on UI -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-book" style="color:var(--primary);margin-right:6px;"></i> Dokumentasi API Integrasi Lengkap (ERP / Booking / Website)</div>
            </div>
            <div class="card-body" style="padding: 24px;">
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 8px;">1. Parameter Request API (`POST /api/v1/wa/send`)</h4>
                    <table class="data-table" style="font-size: 13px; width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F8FAFC; text-align: left;">
                                <th style="padding: 8px 12px; border: 1px solid #E2E8F0;">Parameter</th>
                                <th style="padding: 8px 12px; border: 1px solid #E2E8F0;">Tipe</th>
                                <th style="padding: 8px 12px; border: 1px solid #E2E8F0;">Status</th>
                                <th style="padding: 8px 12px; border: 1px solid #E2E8F0;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><code style="color: var(--primary);">target</code></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">String</td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><span class="badge badge-danger">Wajib</span></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">Nomor tujuan WhatsApp (misal: 08123456789 atau 628123456789).</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><code style="color: var(--primary);">message</code></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">String</td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><span class="badge badge-warning">Opsional</span></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">Isi pesan teks atau caption media/file. Wajib jika tidak mengirim file.</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><code style="color: var(--primary);">url</code> / <code style="color: var(--primary);">file</code></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">String (URL)</td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><span class="badge badge-warning">Opsional</span></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">URL publik berkas (PDF, DOCX, XLSX, ZIP, Gambar, Audio, Video). <strong style="color: #EF4444;">Maksimal 16 MB per file</strong> (Otomatis rejected jika melebihi limit).</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><code style="color: var(--primary);">filename</code></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">String</td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;"><span class="badge badge-warning">Opsional</span></td>
                                <td style="padding: 8px 12px; border: 1px solid #E2E8F0;">Nama lampiran berkas saat diterima pengguna (misal: <code style="font-size:11px;">Nota_#902.pdf</code>).</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 12px; padding: 10px 14px; background: rgba(239, 68, 68, 0.08); border-left: 3px solid #EF4444; border-radius: 6px; font-size: 12px; color: #991B1B;">
                        <i class="fa-solid fa-shield-halved" style="margin-right: 4px;"></i> <strong>Ketentuan Ukuran Berkas:</strong> Seluruh pengiriman berkas/media dibatasi maksimal <strong>16 MB</strong>. Gateway akan memeriksa ukuran berkas via HTTP Header sebelum mengunduh. Jika berkas melebihi 16 MB, request API akan ditolak secara otomatis dengan HTTP Response 422/500.
                    </div>
                </div>


                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 8px;">2. Contoh Kode Integrasi Berbagai Bahasa Program</h4>

                    <div style="background: #0F172A; border-radius: 12px; padding: 16px; color: #F8FAFC;">
                        <div style="font-size: 12px; color: #94A3B8; margin-bottom: 8px; font-weight: 600;">a) cURL / Terminal:</div>
                        <pre style="color: #34D399; font-size: 12px; font-family: monospace; margin: 0 0 16px 0; overflow-x: auto;">
curl -X POST "{{ url('/api/v1/wa/send') }}" \
  -H "X-WA-API-KEY: {{ $device->api_key }}" \
  -H "Content-Type: application/json" \
  -d '{
    "target": "08123456789",
    "message": "Berikut invoice reservasi booking Anda.",
    "url": "https://domain.com/invoices/inv-1002.pdf",
    "filename": "Invoice_Reservasi_#1002.pdf"
  }'</pre>

                        <div style="font-size: 12px; color: #94A3B8; margin-bottom: 8px; font-weight: 600;">b) PHP (Laravel Http Client):</div>
                        <pre style="color: #60A5FA; font-size: 12px; font-family: monospace; margin: 0 0 16px 0; overflow-x: auto;">
use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'X-WA-API-KEY' => '{{ $device->api_key }}',
])->post('{{ url('/api/v1/wa/send') }}', [
    'target'   => '08123456789',
    'message'  => 'Notifikasi transaksi ERP #10089',
    'url'      => 'https://domain.com/storage/invoice-10089.pdf',
    'filename' => 'Invoice_#10089.pdf',
]);</pre>

                        <div style="font-size: 12px; color: #94A3B8; margin-bottom: 8px; font-weight: 600;">c) JavaScript (Node.js / Axios):</div>
                        <pre style="color: #FBBF24; font-size: 12px; font-family: monospace; margin: 0; overflow-x: auto;">
const axios = require('axios');

axios.post('{{ url('/api/v1/wa/send') }}', {
  target: '08123456789',
  message: 'Booking konfirmasi diterima!',
  url: 'https://domain.com/proof.png'
}, {
  headers: { 'X-WA-API-KEY': '{{ $device->api_key }}' }
});</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateFileSize(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var maxBytes = 16 * 1024 * 1024; // 16MB
        if (file.size > maxBytes) {
            var sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Melebihi Batas',
                    text: 'File "' + file.name + '" berukuran ' + sizeMb + ' MB. Maksimal ukuran file yang dapat dikirim adalah 16 MB.',
                    confirmButtonColor: '#EF4444'
                });
            } else {
                alert('File ' + file.name + ' berukuran ' + sizeMb + ' MB. Maksimal 16 MB.');
            }
        }
    }
}

function handleTestSendAjax(e, form) {
    e.preventDefault();

    var btn = form.querySelector('button[type="submit"]');
    var origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';

    var formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = origHtml;

        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Pesan Berhasil Dikirim!',
                    text: data.message || 'Pesan / File uji coba telah berhasil dikirim ke nomor tujuan.',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            } else {
                alert(data.message || 'Pesan berhasil dikirim!');
            }
            form.reset();
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim',
                    text: data.error || 'Terjadi kesalahan saat pengiriman.',
                    confirmButtonColor: '#EF4444'
                });
            } else {
                alert(data.error || 'Gagal mengirim pesan');
            }
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Sistem',
                text: 'Gagal menghubungi server.',
                confirmButtonColor: '#EF4444'
            });
        } else {
            alert('Kesalahan sistem saat menghubungi server.');
        }
    });
}

(function() {


    var isConnected = {{ $device->status === 'connected' ? 'true' : 'false' }};
    var ajaxUrl = "{{ route('customer.whatsapp-devices.status-ajax', $device->uuid) }}";

    // Polling Realtime AJAX untuk mendeteksi Scan QR selesai & Sesi Berakhir
    var pollTimer = setInterval(function() {
        fetch(ajaxUrl)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var container = document.getElementById('qrCodeContainer');
                var featuresContainer = document.getElementById('connectedFeaturesContainer');

                // 1. Jika terhubung & sebelumnya belum terhubung
                if (data.status === 'connected' && !isConnected) {
                    isConnected = true;
                    if (container) {
                        container.innerHTML = `
                            <div style="margin: 20px 0; padding: 20px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; width: 100%; box-sizing: border-box;">
                                <div style="width: 56px; height: 56px; border-radius: 50%; background: #10B981; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px auto;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div style="font-weight: 700; color: #047857; font-size: 16px;">WhatsApp Terhubung!</div>
                                <div style="font-size: 13px; color: #475569; margin-top: 4px;">Nomor: ` + (data.phone_number ? '+' + data.phone_number : 'Aktif') + `</div>
                                <p style="font-size: 11px; color: #10B981; margin-top: 8px; font-weight: 600;">Kredensial API & Fitur Uji Coba Telah Aktif di Sebelah Kanan</p>
                            </div>
                        `;
                    }

                    if (featuresContainer) {
                        featuresContainer.style.display = 'flex';
                    }

                    // Tampilkan SweetAlert2 Sukses Terhubung
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'WhatsApp Terhubung!',
                            text: 'Nomor WhatsApp Anda (' + (data.phone_number ? '+' + data.phone_number : 'Aktif') + ') telah berhasil dihubungkan!',
                            confirmButtonColor: '#10B981',
                            confirmButtonText: 'Buka API & Dokumentasi'
                        });
                    }
                } 
                // 2. Update QR Image jika ada QR baru dan belum terhubung
                else if (data.qr_code && !isConnected) {
                    var imgEl = document.getElementById('qrImageElement');
                    if (imgEl && imgEl.src !== data.qr_code) {
                        imgEl.src = data.qr_code;
                    }
                }
                // 3. Jika sesi berakhir / disconnected setelah sebelumnya terhubung
                else if (data.status === 'disconnected' && isConnected) {
                    isConnected = false;
                    if (featuresContainer) {
                        featuresContainer.style.display = 'none';
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesi WhatsApp Berakhir',
                            text: 'Koneksi terputus. Silakan klik tombol refresh untuk scan barcode ulang.',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                }
            })
            .catch(function(e) {});
    }, 3000);
})();
</script>
@endsection
