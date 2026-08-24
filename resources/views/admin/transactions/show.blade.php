@extends('layouts.admin')

@section('title', 'Transaction Detail #' . ($transaction->invoice_number ?? $transaction->id) . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.transactions.index') }}">Transactions</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Invoice #{{ $transaction->invoice_number ?? $transaction->id }}</h1>
        <p class="page-subtitle">Rincian transaksi pembayaran, mutasi gateway, dan verifikasi bukti transfer.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

<div class="grid-2" style="align-items:start;gap:24px;">

    {{-- Left: Transaction & Customer Details --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Main Info Card --}}
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="card-title">Ringkasan Tagihan</div>
                <div>
                    @if($transaction->status === 'paid')
                        <span class="badge badge-success" style="font-size:13px;padding:6px 12px;"><i class="fa-solid fa-circle-check"></i> PAID (LUNAS)</span>
                    @elseif($transaction->status === 'pending')
                        <span class="badge badge-warning" style="font-size:13px;padding:6px 12px;"><i class="fa-solid fa-clock"></i> PENDING VERIFIKASI</span>
                    @elseif($transaction->status === 'failed')
                        <span class="badge badge-danger" style="font-size:13px;padding:6px 12px;"><i class="fa-solid fa-circle-xmark"></i> FAILED / DITOLAK</span>
                    @else
                        <span class="badge badge-muted" style="font-size:13px;padding:6px 12px;">{{ strtoupper($transaction->status) }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
                <div class="stats-row">
                    <span class="text-sm text-muted">Nomor Invoice</span>
                    <span class="font-mono font-bold">{{ $transaction->invoice_number ?? '-' }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Tipe Transaksi</span>
                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $transaction->type ?? 'Subscription')) }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Gross Amount</span>
                    <span>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                </div>
                @if(($transaction->voucher_discount ?? 0) > 0)
                    <div class="stats-row">
                        <span class="text-sm text-success">Diskon / Voucher</span>
                        <span class="font-semibold text-success">- Rp {{ number_format($transaction->voucher_discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                @php
                    $adminGross = (float) $transaction->gross_amount;
                    $adminDiscount = (float) ($transaction->voucher_discount ?? 0);
                    $adminSubtotal = (float) ($transaction->subtotal_amount > 0 ? $transaction->subtotal_amount : max(0, $adminGross - $adminDiscount));
                    $adminTax = (float) ($transaction->tax_amount > 0 ? $transaction->tax_amount : round($adminSubtotal * 0.11, 2));
                @endphp
                <div class="stats-row">
                    <span class="text-sm text-muted">Subtotal (DPP)</span>
                    <span class="font-semibold">Rp {{ number_format($adminSubtotal, 0, ',', '.') }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Pajak (PPN 11%)</span>
                    <span class="font-semibold text-danger">+ Rp {{ number_format($adminTax, 0, ',', '.') }}</span>
                </div>
                <div class="divider"></div>
                <div class="stats-row" style="padding:12px;background:var(--bg-secondary);border-radius:var(--radius);border:1px solid var(--border);">
                    <span class="font-bold text-base">Total Bayar (Net Amount)</span>
                    <span class="font-bold text-2xl" style="color:var(--primary);">
                        Rp {{ number_format($transaction->net_amount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Tanggal Transaksi</span>
                    <span class="text-sm">{{ $transaction->created_at->format('d M Y H:i:s') }}</span>
                </div>
                @if($transaction->paid_at)
                    <div class="stats-row">
                        <span class="text-sm text-muted">Tanggal Lunas</span>
                        <span class="text-sm font-semibold text-success">{{ $transaction->paid_at->format('d M Y H:i:s') }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Customer Info Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informasi Pelanggan</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <div class="stats-row">
                    <span class="text-sm text-muted">Nama Pelanggan</span>
                    <span class="font-bold">{{ $transaction->customer->name ?? 'N/A' }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Email</span>
                    <span>{{ $transaction->customer->email ?? 'N/A' }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Perusahaan / Bisnis</span>
                    <span>{{ $transaction->customer->companyProfile->company_name ?? $transaction->customer->business_name ?? '—' }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">No. WhatsApp</span>
                    <span>{{ $transaction->customer->phone ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Subscription / Project / AI Top Up Card --}}
        @if($transaction->subscription)
            @php
                $subPlan = $transaction->subscription->subscriptionPlan;
                $subProd = $subPlan?->product ?? $transaction->subscription->license?->product;
            @endphp
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Layanan Langganan</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <div class="stats-row">
                        <span class="text-sm text-muted">Produk ERP</span>
                        <span class="font-bold text-primary">{{ $subProd?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Paket Plan</span>
                        <span class="font-semibold">{{ $subPlan?->name ?? 'Plan' }} ({{ $subPlan?->duration_months ?? 1 }} Bulan)</span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Status Langganan</span>
                        <span><span class="badge badge-success">{{ strtoupper($transaction->subscription->status) }}</span></span>
                    </div>
                </div>
            </div>
        @elseif($transaction->type === 'ai_token_topup' || $transaction->aiTokenPurchase)
            @php
                $aiPur = $transaction->aiTokenPurchase ?? \App\Models\AiTokenPurchase::where('transaction_id', $transaction->id)->first();
            @endphp
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-brain text-primary" style="margin-right: 6px;"></i> Informasi Top-Up AI Token</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <div class="stats-row">
                        <span class="text-sm text-muted">Paket Top-Up</span>
                        <span class="font-bold text-primary">{{ $aiPur?->package?->name ?? 'AI Token Package' }}</span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Jumlah Kuota Token</span>
                        <span class="font-mono font-bold text-success">+{{ number_format($aiPur?->tokens_amount ?? 0) }} Token</span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Masa Berlaku Token</span>
                        <span class="font-semibold">30 Hari sejak pembayaran disetujui</span>
                    </div>
                    @if($aiPur?->license)
                        <div class="stats-row">
                            <span class="text-sm text-muted">Terkait Lisensi</span>
                            <span>{{ $aiPur->license->product->name ?? 'Produk' }} ({{ substr($aiPur->license_id, 0, 8) }}...)</span>
                        </div>
                    @endif
                    <div class="stats-row">
                        <span class="text-sm text-muted">Status Kredit Kuota</span>
                        <span>
                            @if($aiPur?->status === 'completed')
                                <span class="badge badge-success">SUDAH DIKREDITKAN KE WALLET</span>
                            @else
                                <span class="badge badge-warning">MENUNGGU VERIFIKASI ADMIN</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @elseif($transaction->project)
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Proyek</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <div class="stats-row">
                        <span class="text-sm text-muted">Nama Proyek</span>
                        <span class="font-bold text-primary">{{ $transaction->project->project_name ?? 'N/A' }}</span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Deskripsi Termin</span>
                        <span>{{ $transaction->description ?? 'Project milestone payment' }}</span>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Right: Payment Gateway / Manual Transfer & Verification --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Gateway Details Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Metode Pembayaran</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                <div class="stats-row">
                    <span class="text-sm text-muted">Tipe Gateway</span>
                    <span>
                        @if($transaction->isManualTransfer())
                            <span class="badge badge-warning">
                                <i class="fa-solid fa-building-columns"></i> Transfer Bank Manual
                            </span>
                        @else
                            <span class="badge badge-purple">
                                <i class="fa-solid fa-bolt"></i> Midtrans Gateway
                            </span>
                        @endif
                    </span>
                </div>

                @if(!$transaction->isManualTransfer())
                    <div class="stats-row">
                        <span class="text-sm text-muted">Midtrans Order ID</span>
                        <span class="font-mono text-xs">{{ $transaction->midtrans_order_id ?? '—' }}</span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Midtrans Status</span>
                        <span class="badge badge-muted">{{ strtoupper($transaction->midtrans_status ?? 'N/A') }}</span>
                    </div>
                @else
                    <div class="stats-row">
                        <span class="text-sm text-muted">Nama Pengirim Rekening</span>
                        <span class="font-bold">{{ $transaction->sender_name ?? '—' }}</span>
                    </div>
                    @if($transaction->payment_notes)
                        <div class="stats-row">
                            <span class="text-sm text-muted">Catatan Pengirim</span>
                            <span class="text-sm font-semibold">{{ $transaction->payment_notes }}</span>
                        </div>
                    @endif
                    <div class="stats-row">
                        <span class="text-sm text-muted">Waktu Upload Bukti</span>
                        <span class="text-xs text-muted">{{ $transaction->payment_proof_uploaded_at?->format('d M Y H:i:s') ?? $transaction->updated_at->format('d M Y H:i:s') }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Proof of Payment Card (for manual transfer or when proof exists) --}}
        @if($transaction->isManualTransfer() || $transaction->payment_proof)
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <div class="card-title">Bukti Transfer Pelanggan</div>
                    @if($transaction->payment_proof)
                        <a href="{{ $transaction->payment_proof_url }}" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-external-link-alt"></i> Buka File Asli
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($transaction->payment_proof)
                        @php
                            $ext = strtolower(pathinfo($transaction->payment_proof, PATHINFO_EXTENSION));
                        @endphp

                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                            <div style="background:var(--bg);padding:10px;border-radius:var(--radius);text-align:center;border:1px solid var(--border);">
                                <a href="{{ $transaction->payment_proof_url }}" target="_blank" title="Klik untuk memperbesar">
                                    <img src="{{ $transaction->payment_proof_url }}" alt="Bukti Transfer" style="max-height:360px;max-width:100%;border-radius:8px;object-fit:contain;margin:0 auto;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                                </a>
                            </div>
                        @else
                            <div style="text-align:center;padding:30px 20px;background:var(--bg);border-radius:var(--radius);border:1px solid var(--border);">
                                <i class="fa-solid fa-file-pdf" style="font-size:48px;color:var(--danger);margin-bottom:12px;display:block;"></i>
                                <div class="font-bold text-sm">Dokumen Bukti Transfer ({{ strtoupper($ext) }})</div>
                                <a href="{{ $transaction->payment_proof_url }}" target="_blank" class="btn btn-primary btn-sm mt-3">
                                    <i class="fa-solid fa-download"></i> Unduh Dokumen Bukti
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning" style="margin:0;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Pelanggan belum mengunggah file bukti pembayaran.
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Verification Actions Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Aksi & Status Verifikasi Admin</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                @if($transaction->status === 'paid')
                    <div class="alert alert-success" style="margin:0;">
                        <div class="font-bold"><i class="fa-solid fa-circle-check"></i> Transaksi Berhasil Diverifikasi</div>
                        <div class="text-xs mt-1">
                            Diverifikasi oleh: <strong>{{ $transaction->verifier->name ?? 'Admin / Webhook' }}</strong>
                            @if($transaction->verified_at)
                                pada {{ $transaction->verified_at->format('d M Y H:i') }}
                            @endif
                        </div>
                    </div>
                @elseif($transaction->status === 'failed')
                    <div class="alert alert-danger" style="margin:0;">
                        <div class="font-bold"><i class="fa-solid fa-circle-xmark"></i> Transaksi / Bukti Pembayaran Ditolak</div>
                        @if($transaction->rejection_reason)
                            <div class="text-xs mt-1">Alasan Penolakan: <strong>{{ $transaction->rejection_reason }}</strong></div>
                        @endif
                        <div class="text-xs mt-1 text-muted">Pelanggan dapat mengunggah ulang bukti bayar baru melalui dashboard customer.</div>
                    </div>
                @else
                    {{-- Pending Verification --}}
                    <div class="text-xs text-muted">
                        Pastikan Anda telah memeriksa mutasi rekening bank dan nominal dana yang masuk sebelum menyetujui transaksi.
                    </div>

                    <div style="display:flex;gap:10px;flex-direction:column;">
                        {{-- Approve Button --}}
                        <form method="POST" action="{{ route('admin.transactions.verify', $transaction->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dan memverifikasi pembayaran ini? {{ $transaction->type === 'ai_token_topup' ? 'Kuota token AI sebesar +' . number_format($aiPur?->tokens_amount ?? 0) . ' Token akan langsung dikreditkan ke wallet pelanggan.' : 'Layanan subscription/proyek pelanggan akan langsung diaktifkan.' }}');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-full" style="justify-content:center;font-size:15px;padding:14px;">
                                <i class="fa-solid fa-check-circle"></i> Setujui & Verifikasi Pembayaran
                            </button>
                        </form>

                        {{-- Reject Button triggering form --}}
                        <button type="button" class="btn btn-outline-danger w-full" style="justify-content:center;" onclick="document.getElementById('reject-form-box').style.display = document.getElementById('reject-form-box').style.display === 'none' ? 'block' : 'none';">
                            <i class="fa-solid fa-ban"></i> Tolak Bukti Bayar...
                        </button>

                        <div id="reject-form-box" style="display:none;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-top:6px;">
                            <form method="POST" action="{{ route('admin.transactions.reject', $transaction->id) }}">
                                @csrf
                                <label class="form-label text-xs font-bold" for="rejection_reason">Alasan Penolakan Bukti Bayar <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-textarea" rows="3" placeholder="Contoh: Bukti transfer tidak terbaca / dana belum masuk ke mutasi rekening." required></textarea>
                                <div class="flex justify-end gap-2 mt-3">
                                    <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('reject-form-box').style.display = 'none';">Batal</button>
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak Transaksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
