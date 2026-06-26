@extends('layouts.guest')
@push('styles')
<style>
    .contract-page { padding-top: 100px; padding-bottom: 60px; background: var(--bg); }
    .contract-wrapper {
        max-width: 900px; margin: 0 auto;
    }
    .contract-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    .contract-header {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: #fff;
        padding: 32px 40px;
    }
    .contract-header h1 { font-size: 1.5rem; font-weight: 800; }
    .contract-header p { font-size: 0.9rem; opacity: 0.85; margin-top: 6px; }
    .contract-number {
        background: rgba(255,255,255,0.15);
        border-radius: 8px;
        padding: 8px 14px;
        display: inline-block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 10px;
    }
    .contract-body { padding: 40px; }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 28px;
    }
    .info-card {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
    }
    .info-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; }
    .info-value { font-size: 1rem; font-weight: 600; color: var(--text); margin-top: 4px; }
    .signature-section {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 28px;
        margin-top: 28px;
    }
    .signature-section h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
    .signature-section p { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; }
    #signatureCanvas {
        width: 100%;
        height: 160px;
        border: 2px dashed var(--accent);
        border-radius: 12px;
        background: var(--card);
        cursor: crosshair;
        display: block;
        touch-action: none;
    }
    .sig-actions { display: flex; gap: 12px; margin-top: 14px; }
    .btn-clear {
        padding: 9px 22px; border-radius: 8px;
        border: 1px solid var(--border);
        background: transparent; color: var(--text-muted);
        font-size: 0.85rem; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-clear:hover { border-color: #ef4444; color: #ef4444; }
    .btn-submit {
        padding: 12px 32px; border-radius: 8px;
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #fff; font-size: 0.9rem;
        font-weight: 600; border: none;
        cursor: pointer; transition: all 0.2s;
        flex: 1;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
    .terms-preview {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        max-height: 240px;
        overflow-y: auto;
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .terms-preview h4 { color: var(--text); font-size: 0.9rem; margin-bottom: 6px; }
    .already-signed {
        text-align: center;
        padding: 40px;
        background: rgba(16,185,129,0.08);
        border: 1px solid rgba(16,185,129,0.3);
        border-radius: var(--radius);
        margin-top: 28px;
    }
    .already-signed i { font-size: 3rem; color: #10b981; }
    .already-signed h3 { font-size: 1.2rem; margin-top: 12px; }
    .already-signed p { color: var(--text-muted); font-size: 0.9rem; margin-top: 6px; }
</style>
@endpush

@section('content')
<section class="contract-page">
    <div class="container">
        <div class="contract-wrapper">

            {{-- Breadcrumb --}}
            <div class="mb-4">
                <a href="{{ route('customer.dashboard') }}" style="color:var(--accent); font-size:0.85rem;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success mb-4" style="border-radius:var(--radius); background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#10b981; padding:14px 20px;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif

            <div class="contract-card">
                {{-- Header --}}
                <div class="contract-header">
                    <h1><i class="bi bi-file-earmark-text me-2"></i>Kontrak Lisensi Software</h1>
                    <p>Software License Agreement · COOCA.id</p>
                    <div class="contract-number">
                        <i class="bi bi-hash"></i> {{ $contract->contract_number }}
                    </div>
                </div>

                <div class="contract-body">
                    {{-- Product & License Info --}}
                    <h3 class="mb-3" style="font-size:1rem; font-weight:700;">Detail Lisensi Anda</h3>
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Produk</div>
                            <div class="info-value">{{ $license->product->name }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Kode Lisensi</div>
                            <div class="info-value" style="font-family:monospace;">{{ $license->license_code }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Domain Terdaftar</div>
                            <div class="info-value">{{ $license->domain }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Masa Berlaku</div>
                            <div class="info-value">
                                {{ $license->expires_at ? $license->expires_at->format('d M Y') : 'Lifetime' }}
                            </div>
                        </div>
                    </div>

                    {{-- Terms preview --}}
                    <div class="terms-preview">
                        <h4>Ringkasan Ketentuan / Terms Summary</h4>
                        <strong>Pasal 1 – Definisi:</strong> "Lisensi" adalah hak terbatas, non-eksklusif untuk menggunakan Perangkat Lunak COOCA.<br><br>
                        <strong>Pasal 2 – Pemberian Lisensi:</strong> Lisensi terikat pada domain <strong>{{ $license->domain }}</strong> dan tidak dapat digunakan pada infrastruktur pihak lain.<br><br>
                        <strong>Pasal 3 – Larangan:</strong> Dilarang mendistribusikan ulang, merekayasa balik, menghapus merek COOCA, atau menggunakan di luar domain terdaftar.<br><br>
                        <strong>Pasal 4 – Dukungan:</strong> Dukungan teknis dan pembaruan keamaman disediakan sesuai paket yang dipilih.<br><br>
                        <strong>Pasal 5 – Kerahasiaan Data:</strong> Data berjalan di infrastruktur terisolasi dan tidak akan dibagikan ke pihak ketiga.<br><br>
                        <strong>Pasal 6 – Sengketa:</strong> Diselesaikan secara musyawarah, dan jika tidak berhasil melalui Pengadilan Negeri yang berwenang di Indonesia.<br><br>
                        <em style="font-size:0.8rem;">Dokumen lengkap bilingual (Bahasa Indonesia & English) tersedia dalam PDF yang dapat Anda unduh setelah menandatangani.</em>
                    </div>

                    {{-- Signature Section --}}
                    @if($contract->status === 'signed')
                        <div class="already-signed">
                            <i class="bi bi-patch-check-fill"></i>
                            <h3>Kontrak Sudah Ditandatangani</h3>
                            <p>Ditandatangani pada {{ $contract->signed_at?->format('d M Y, H:i') }} WIB</p>
                            <a href="{{ route('customer.contracts.download', $license->id) }}"
                               class="btn-cooca btn-cooca-primary mt-4" style="display:inline-flex;">
                                <i class="bi bi-download me-2"></i>Download Kontrak PDF
                            </a>
                        </div>
                    @else
                        <div class="signature-section">
                            <h3><i class="bi bi-pen me-2"></i>Tanda Tangan Elektronik</h3>
                            <p>Gambarkan tanda tangan Anda di dalam kotak di bawah ini. Dengan menandatangani, Anda menyetujui seluruh ketentuan perjanjian di atas.</p>

                            <canvas id="signatureCanvas" width="800" height="160"></canvas>

                            <form id="signatureForm"
                                  action="{{ route('customer.contracts.sign', $license->id) }}"
                                  method="POST">
                                @csrf
                                <input type="hidden" name="signature_data" id="signatureDataInput"/>
                                <div class="sig-actions">
                                    <button type="button" class="btn-clear" id="clearBtn">
                                        <i class="bi bi-eraser me-1"></i>Hapus / Clear
                                    </button>
                                    <button type="submit" class="btn-submit" id="submitBtn">
                                        <i class="bi bi-check2-circle me-2"></i>Tandatangani & Download Kontrak
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function() {
    const canvas = document.getElementById('signatureCanvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const form = document.getElementById('signatureForm');
    const clearBtn = document.getElementById('clearBtn');
    const input = document.getElementById('signatureDataInput');

    // Scale canvas to actual display size
    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        ctx.strokeStyle = '#1e40af';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }
    resizeCanvas();

    let drawing = false, hasSig = false;

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const src = e.touches ? e.touches[0] : e;
        return { x: src.clientX - rect.left, y: src.clientY - rect.top };
    }

    function startDraw(e) {
        e.preventDefault();
        drawing = true;
        const p = getPos(e);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
    }
    function draw(e) {
        if (!drawing) return;
        e.preventDefault();
        hasSig = true;
        const p = getPos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    }
    function endDraw() { drawing = false; }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', endDraw);
    canvas.addEventListener('mouseleave', endDraw);
    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', endDraw);

    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSig = false;
    });

    form.addEventListener('submit', function(e) {
        if (!hasSig) {
            e.preventDefault();
            alert('Silakan gambar tanda tangan Anda terlebih dahulu.\nPlease draw your signature first.');
            return;
        }
        input.value = canvas.toDataURL('image/png');
    });
})();
</script>
@endpush
