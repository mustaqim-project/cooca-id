@extends('layouts.customer')
@section('title', 'Project Checkout')
@section('breadcrumb')
    <a href="{{ route('customer.projects.index') }}" class="crumb-link">Projects</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('customer.projects.show', $project->id) }}" class="crumb-link">{{ $project->project_name }}</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Checkout</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-credit-card" style="color:var(--primary);margin-right:10px;"></i>
            Project Checkout
        </h1>
        <p class="page-subtitle">Selesaikan pembayaran untuk termin proyek Anda.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.projects.show', $project->id) }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">Rincian Pembayaran Proyek</div>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:20px;">
        <div>
            <div class="text-xs text-muted font-bold uppercase mb-1">Project</div>
            <div class="font-bold text-lg">{{ $project->project_name }}</div>
        </div>

        <div>
            <div class="text-xs text-muted font-bold uppercase mb-1">Nomor Invoice</div>
            <div class="font-mono text-sm">{{ $invoice->invoice_number }}</div>
        </div>

        <div>
            <div class="text-xs text-muted font-bold uppercase mb-1">Deskripsi Pembayaran</div>
            <div class="text-sm font-semibold">{{ $transaction->description ?? 'Project milestone payment' }}</div>
        </div>

        <div class="divider" style="border-top:1px solid var(--border);"></div>

        <div class="flex justify-between items-center">
            <div class="font-bold text-base">Total Tagihan:</div>
            <div class="text-2xl font-bold" style="color:var(--primary);">
                Rp {{ number_format($transaction->net_amount, 0, ',', '.') }}
            </div>
        </div>

        @if($invoice->status === 'paid')
            <div class="alert alert-success" style="text-align:center;padding:15px;background:#def7ec;color:#03543f;border-radius:var(--radius);font-weight:bold;">
                <i class="fa-solid fa-circle-check"></i> Pembayaran Lunas
            </div>
        @else
            @php
                $tx = $invoice->transaction;
                $hasProof = $tx && $tx->payment_proof;
                $isRejected = $tx && $tx->status === 'failed' && $tx->rejection_reason;
                $bName = \App\Models\Setting::get('payment.bank_transfer.bank_name', 'Bank Central Asia (BCA)');
                $bNumber = \App\Models\Setting::get('payment.bank_transfer.account_number', '8830-8899-8800');
                $bHolder = \App\Models\Setting::get('payment.bank_transfer.account_name', 'PT COOCA TECHNOLOGIES INDONESIA');
                $bInst = \App\Models\Setting::get('payment.bank_transfer.instructions', 'Silakan transfer sesuai jumlah tagihan.');
            @endphp

            @if ($isRejected)
                <div class="alert alert-danger mb-2">
                    <div class="font-bold"><i class="fa-solid fa-circle-xmark"></i> Bukti Pembayaran Sebelumnya Ditolak</div>
                    <div class="text-xs mt-1">Alasan: <strong>{{ $tx->rejection_reason }}</strong></div>
                </div>
            @elseif ($hasProof && $tx->status === 'pending')
                <div class="alert alert-warning mb-2" style="border-radius:var(--radius);padding:12px;">
                    <div class="font-bold text-sm"><i class="fa-solid fa-clock"></i> Bukti Transfer Sedang Diverifikasi</div>
                    <div class="text-xs mt-1">Pengirim: <strong>{{ $tx->sender_name ?? 'Pelanggan' }}</strong></div>
                    <div class="mt-2">
                        <a href="{{ $tx->payment_proof_url }}" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-file-invoice"></i> Lihat Bukti Bayar
                        </a>
                    </div>
                </div>
            @endif

            {{-- Method tabs --}}
            <div class="payment-methods-grid">
                <label class="payment-method-card active" id="proj-card-midtrans" onclick="toggleProjPay('midtrans')">
                    <input type="radio" name="proj_pay_type" value="midtrans" checked onchange="toggleProjPay('midtrans')" style="display:none;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-bolt" style="color:var(--primary);"></i>
                        <strong style="font-size:13px;">Midtrans Gateway</strong>
                    </div>
                    <span class="text-xs text-muted">QRIS, VA, E-Wallet</span>
                </label>

                <label class="payment-method-card" id="proj-card-manual" onclick="toggleProjPay('manual')">
                    <input type="radio" name="proj_pay_type" value="manual" onchange="toggleProjPay('manual')" style="display:none;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-building-columns" style="color:var(--text-muted);"></i>
                        <strong style="font-size:13px;">Transfer Manual</strong>
                    </div>
                    <span class="text-xs text-muted">Upload Bukti Bayar</span>
                </label>
            </div>

            {{-- Midtrans Pay --}}
            <div id="proj-sec-midtrans" style="display:flex;flex-direction:column;gap:10px;">
                <button id="pay-button" class="btn btn-primary w-full" style="justify-content:center;font-size:15px;padding:14px;">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang via Midtrans
                </button>

                @if(isset($snapUrl) && $snapUrl)
                    <a href="{{ $snapUrl }}" class="btn btn-outline w-full" style="justify-content:center;font-size:13px;" target="_blank">
                        <i class="fa-solid fa-external-link-alt"></i> Buka di Halaman Baru
                    </a>
                @endif

                @php
                    $isSandbox = config('services.midtrans.sandbox', true);
                    $midtransJsUrl = $isSandbox ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js';
                    $clientKey = config('services.midtrans.client_key');
                @endphp
                <script src="{{ $midtransJsUrl }}" data-client-key="{{ $clientKey }}"></script>
                <script>
                    const payBtn = document.getElementById('pay-button');
                    if (payBtn && '{{ $snapToken ?? "" }}') {
                        payBtn.onclick = function () {
                            snap.pay('{{ $snapToken }}', {
                                onSuccess: function(result) {
                                    window.location.href = '{{ route('customer.payments.success') }}';
                                },
                                onPending: function(result) {
                                    window.location.href = '{{ route('customer.payments.pending') }}';
                                },
                                onError: function(result) {
                                    window.location.href = '{{ route('customer.payments.failed') }}';
                                },
                                onClose: function() {}
                            });
                        };
                    }
                </script>
            </div>

            {{-- Manual Transfer Form --}}
            <div id="proj-sec-manual" style="display:none;flex-direction:column;gap:12px;">
                @php
                    $pBanks = \App\Models\CompanyBankAccount::active()->ordered()->get();
                @endphp

                <div style="display:flex;flex-direction:column;gap:8px;">
                    @if($pBanks->count() > 0)
                        @foreach($pBanks as $pb)
                            <div class="bank-account-card">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                                    <span class="font-bold text-sm" style="color:var(--primary);">{{ $pb->bank_name }}</span>
                                    @if($pb->is_primary)
                                        <span class="badge badge-warning" style="font-size:10px;"><i class="fa-solid fa-star"></i> Utama</span>
                                    @endif
                                </div>
                                <div class="bank-account-number-box">
                                    <div class="font-mono font-bold text-base">{{ $pb->account_number }}</div>
                                </div>
                                <div class="text-xs text-muted">a/n <strong style="color:var(--text);">{{ $pb->account_holder }}</strong></div>
                            </div>
                        @endforeach
                    @else
                        <div class="bank-account-card">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                <span class="text-xs font-bold uppercase text-muted">Rekening Tujuan</span>
                                <span class="badge badge-primary">{{ $bName }}</span>
                            </div>
                            <div class="bank-account-number-box">
                                <div class="font-mono font-bold text-base">{{ $bNumber }}</div>
                            </div>
                            <div class="text-xs text-muted">a/n <strong style="color:var(--text);">{{ $bHolder }}</strong></div>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('customer.payments.store') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:10px;">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    <input type="hidden" name="payment_type" value="manual_transfer">

                    <div>
                        <label class="form-label text-xs font-bold" for="proj-sender-name">Nama Pemilik Rekening Pengirim <span class="text-danger">*</span></label>
                        <input type="text" name="sender_name" id="proj-sender-name" class="form-input" placeholder="Contoh: Budi Santoso" value="{{ old('sender_name', $tx?->sender_name) }}" required>
                    </div>

                    <div>
                        <label class="form-label text-xs font-bold" for="proj-proof">Upload Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" name="payment_proof" id="proj-proof" class="form-input" accept="image/jpeg,image/png,image/webp,application/pdf" required>
                    </div>

                    <div>
                        <label class="form-label text-xs font-bold" for="proj-notes">Catatan (Opsional)</label>
                        <input type="text" name="payment_notes" id="proj-notes" class="form-input" placeholder="Catatan pembayaran">
                    </div>

                    <button type="submit" class="btn btn-success w-full" style="justify-content:center;font-size:15px;padding:14px;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleProjPay(type) {
    const cardM = document.getElementById('proj-card-midtrans');
    const cardT = document.getElementById('proj-card-manual');
    const secM = document.getElementById('proj-sec-midtrans');
    const secT = document.getElementById('proj-sec-manual');

    if (type === 'manual') {
        if (cardM) cardM.classList.remove('active');
        if (cardT) cardT.classList.add('active');
        if (secM) secM.style.display = 'none';
        if (secT) secT.style.display = 'flex';
    } else {
        if (cardM) cardM.classList.add('active');
        if (cardT) cardT.classList.remove('active');
        if (secM) secM.style.display = 'flex';
        if (secT) secT.style.display = 'none';
    }
}
</script>
@endpush
