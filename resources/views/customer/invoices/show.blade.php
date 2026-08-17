@extends('layouts.customer')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('breadcrumb')
    <a href="{{ route('customer.invoices.index') }}" class="crumb-link">Invoices</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">{{ $invoice->invoice_number }}</span>
@endsection
@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="fa-solid fa-file-invoice"
                    style="color:var(--primary);margin-right:10px;"></i>Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="page-subtitle">Issued on
                {{ $invoice->issued_at?->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('customer.invoices.download', $invoice->id) }}" class="btn btn-outline">
                <i class="fa-solid fa-download"></i> Download PDF
            </a>
            <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card" style="max-width:800px;margin:0 auto;">
        <div class="card-body" style="padding:32px;">
            {{-- Invoice Header --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <div class="font-bold text-2xl" style="color:var(--primary);">COOCA.ID</div>
                    <div class="text-xs text-muted">PT COOCA TECHNOLOGIES INDONESIA</div>
                    <div class="text-xs text-muted">Jakarta, Indonesia</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-xl">{{ $invoice->invoice_number }}</div>
                    <div class="mt-2">
                        @if ($invoice->status === 'paid')
                            <span class="badge badge-success" style="font-size:14px;padding:6px 14px;">PAID</span>
                        @elseif($invoice->status === 'overdue')
                            <span class="badge badge-danger" style="font-size:14px;padding:6px 14px;">OVERDUE</span>
                        @else
                            <span class="badge badge-warning" style="font-size:14px;padding:6px 14px;">PENDING
                                PAYMENT</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid-2 mb-6" style="background:var(--bg);border-radius:var(--radius);padding:18px;">
                <div>
                    <div class="text-xs text-muted font-bold uppercase mb-1">Billed To</div>
                    <div class="font-bold text-sm">
                        {{ auth('customer')->user()->business_name ?? auth('customer')->user()->name }}</div>
                    <div class="text-xs text-muted">{{ auth('customer')->user()->email }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-muted font-bold uppercase mb-1">Invoice Dates</div>
                    <div class="text-xs">Issued: <strong>{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</strong></div>
                    <div class="text-xs">Due: <strong
                            style="color:var(--danger);">{{ $invoice->due_at?->format('d M Y') ?? '—' }}</strong></div>
                </div>
            </div>

            {{-- Table --}}
            <table class="data-table mb-6">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="font-bold text-sm">
                                {{ $invoice->transaction?->subscription?->product?->name ?? 'COOCA SaaS Subscription' }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $invoice->transaction?->subscription?->subscriptionPlan?->name ?? 'Service Plan' }}
                            </div>
                        </td>
                        <td class="text-right font-bold text-base">
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-between items-center pt-4" style="border-top:2px solid var(--border);">
                <div class="text-sm font-bold">Total Amount Due:</div>
                <div class="text-2xl font-bold" style="color:var(--primary);">
                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                </div>
            </div>

            {{-- Payment Section for Unpaid Invoices --}}
            @if (in_array($invoice->status, ['issued', 'overdue', 'pending', 'unpaid']))
                @php
                    $tx = $invoice->transaction;
                    $hasProof = $tx && $tx->payment_proof;
                    $isRejected = $tx && $tx->status === 'failed' && $tx->rejection_reason;
                    $bName = \App\Models\Setting::get('payment.bank_transfer.bank_name', 'Bank Central Asia (BCA)');
                    $bNumber = \App\Models\Setting::get('payment.bank_transfer.account_number', '8830-8899-8800');
                    $bHolder = \App\Models\Setting::get('payment.bank_transfer.account_name', 'PT COOCA TECHNOLOGIES INDONESIA');
                    $bInst = \App\Models\Setting::get('payment.bank_transfer.instructions', 'Silakan transfer sesuai jumlah tagihan.');
                @endphp

                <div class="divider my-6"></div>

                @if ($isRejected)
                    <div class="alert alert-danger mb-4">
                        <div class="font-bold"><i class="fa-solid fa-circle-xmark"></i> Bukti Pembayaran Sebelumnya Ditolak</div>
                        <div class="text-xs mt-1">Alasan: <strong>{{ $tx->rejection_reason }}</strong></div>
                        <div class="text-xs mt-1">Silakan lakukan transfer ulang atau unggah bukti transfer yang valid di bawah ini.</div>
                    </div>
                @elseif ($hasProof && $tx->status === 'pending')
                    <div class="alert alert-warning mb-4" style="background:#fffbeb;border:1px solid #fef3c7;color:#92400e;border-radius:var(--radius);padding:16px;">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-bold text-sm"><i class="fa-solid fa-clock"></i> Bukti Transfer Sedang Diverifikasi</div>
                                <div class="text-xs mt-1">Pengirim: <strong>{{ $tx->sender_name ?? 'Pelanggan' }}</strong> · Diunggah pada: {{ $tx->payment_proof_uploaded_at?->format('d M Y H:i') ?? $tx->updated_at->format('d M Y H:i') }}</div>
                                <div class="text-xs mt-1 text-muted">Tim kami sedang memeriksa mutasi rekening. Transaksi akan otomatis aktif setelah diverifikasi.</div>
                            </div>
                            <div>
                                <a href="{{ $tx->payment_proof_url }}" target="_blank" class="btn btn-outline btn-sm" style="background:#fff;">
                                    <i class="fa-solid fa-file-invoice"></i> Lihat Bukti Bayar
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card" style="border:1px solid var(--border);box-shadow:none;background:var(--bg);">
                    <div class="card-header" style="background:transparent;border-bottom:1px solid var(--border);">
                        <div class="card-title text-base font-bold">Pilih Cara Pembayaran Tagihan</div>
                    </div>
                    <div class="card-body" style="padding:20px;display:flex;flex-direction:column;gap:16px;">

                        {{-- Tab Switcher --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <label class="payment-method-card" id="inv-card-midtrans" onclick="toggleInvPayment('midtrans')" style="border:2px solid var(--primary);background:rgba(67,97,238,0.06);padding:14px;border-radius:var(--radius);cursor:pointer;display:flex;flex-direction:column;gap:4px;transition:all 0.2s;">
                                <input type="radio" name="inv_payment_type" value="midtrans" checked onchange="toggleInvPayment('midtrans')" style="display:none;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-bolt" style="color:var(--primary);font-size:16px;"></i>
                                    <strong style="font-size:13px;">Payment Gateway</strong>
                                </div>
                                <span class="text-xs text-muted">Midtrans (QRIS, VA Bank, E-Wallet)</span>
                                <span class="badge badge-success" style="align-self:flex-start;font-size:10px;margin-top:2px;">Otomatis Instan</span>
                            </label>

                            <label class="payment-method-card" id="inv-card-manual" onclick="toggleInvPayment('manual_transfer')" style="border:1px solid var(--border);background:#fff;padding:14px;border-radius:var(--radius);cursor:pointer;display:flex;flex-direction:column;gap:4px;transition:all 0.2s;">
                                <input type="radio" name="inv_payment_type" value="manual_transfer" onchange="toggleInvPayment('manual_transfer')" style="display:none;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-building-columns" style="color:#6c757d;font-size:16px;"></i>
                                    <strong style="font-size:13px;">Transfer Bank Manual</strong>
                                </div>
                                <span class="text-xs text-muted">Transfer Rekening Resmi PT COOCA</span>
                                <span class="badge badge-warning" style="align-self:flex-start;font-size:10px;margin-top:2px;">Upload Bukti Transfer</span>
                            </label>
                        </div>

                        {{-- Midtrans Form --}}
                        <div id="inv-sec-midtrans" style="display:flex;flex-direction:column;gap:12px;">
                            <form method="POST" action="{{ route('customer.payments.store') }}">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                <input type="hidden" name="payment_type" value="midtrans">
                                <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content:center;font-size:15px;padding:14px;">
                                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang via Midtrans (Instan)
                                </button>
                            </form>
                        </div>

                        {{-- Manual Transfer Form --}}
                        <div id="inv-sec-manual" style="display:none;flex-direction:column;gap:14px;">
                            @php
                                $cBanks = \App\Models\CompanyBankAccount::active()->ordered()->get();
                            @endphp

                            <div style="display:flex;flex-direction:column;gap:8px;">
                                <div class="text-xs font-bold uppercase text-muted" style="letter-spacing:0.5px;">
                                    <i class="fa-solid fa-building-columns" style="color:var(--primary);margin-right:4px;"></i> Rekening Tujuan Transfer
                                </div>

                                @if($cBanks->count() > 0)
                                    @foreach($cBanks as $cb)
                                        <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:12px 14px;">
                                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                                                <span class="font-bold text-sm" style="color:var(--primary);">{{ $cb->bank_name }}</span>
                                                @if($cb->is_primary)
                                                    <span class="badge badge-warning" style="font-size:10px;"><i class="fa-solid fa-star"></i> Utama</span>
                                                @endif
                                            </div>
                                            <div style="display:flex;align-items:center;justify-content:space-between;background:var(--bg);padding:8px 12px;border-radius:8px;border:1px dashed var(--primary);margin:4px 0;">
                                                <div class="font-mono font-bold text-base">{{ $cb->account_number }}</div>
                                                <button type="button" class="btn btn-ghost btn-xs" onclick="copyInvAccNum('{{ $cb->account_number }}')">
                                                    <i class="fa-solid fa-copy"></i> Salin
                                                </button>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;" class="text-muted">
                                                <span>a/n <strong>{{ $cb->account_holder }}</strong></span>
                                                @if($cb->qr_code_url)
                                                    <a href="{{ $cb->qr_code_url }}" target="_blank" class="badge badge-success" style="font-size:10px;text-decoration:none;">
                                                        <i class="fa-solid fa-qrcode"></i> QRIS
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:16px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                            <span class="text-xs font-bold uppercase text-muted">Rekening Tujuan</span>
                                            <span class="badge badge-primary">{{ $bName }}</span>
                                        </div>
                                        <div style="display:flex;align-items:center;justify-content:space-between;background:var(--bg);padding:10px 14px;border-radius:8px;border:1px dashed var(--primary);margin-bottom:8px;">
                                            <div>
                                                <div class="text-xs text-muted">Nomor Rekening:</div>
                                                <div class="font-mono font-bold text-lg">{{ $bNumber }}</div>
                                            </div>
                                            <button type="button" class="btn btn-ghost btn-sm" onclick="copyInvAccNum('{{ $bNumber }}')">
                                                <i class="fa-solid fa-copy"></i> Salin
                                            </button>
                                        </div>
                                        <div class="text-xs text-muted">Atas Nama: <strong style="color:var(--text);">{{ $bHolder }}</strong></div>
                                        <div class="text-xs text-muted mt-2" style="font-style:italic;">{{ $bInst }}</div>
                                    </div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('customer.payments.store') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:12px;">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                <input type="hidden" name="payment_type" value="manual_transfer">

                                <div>
                                    <label class="form-label text-xs font-bold" for="inv-sender-name">
                                        Nama Pemilik Rekening Pengirim <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="sender_name" id="inv-sender-name" class="form-input" placeholder="Contoh: Budi Santoso" value="{{ old('sender_name', $tx?->sender_name) }}" required>
                                </div>

                                <div>
                                    <label class="form-label text-xs font-bold" for="inv-payment-proof">
                                        Upload Bukti Bayar / Struk Transfer <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="payment_proof" id="inv-payment-proof" class="form-input" accept="image/jpeg,image/png,image/webp,application/pdf" required onchange="previewInvProof(this)">
                                    <div class="text-xs text-muted mt-1">Format: JPG, PNG, WEBP, PDF (Maksimal 5MB).</div>
                                    <div id="inv-proof-preview-wrap" style="display:none;margin-top:10px;text-align:center;">
                                        <img id="inv-proof-preview-img" src="" alt="Preview Bukti" style="max-height:160px;border-radius:8px;border:1px solid var(--border);margin:0 auto;object-fit:contain;">
                                        <div id="inv-proof-file-name" class="text-xs text-muted mt-1"></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label text-xs font-bold" for="inv-payment-notes">
                                        Catatan Pembayaran (Opsional)
                                    </label>
                                    <input type="text" name="payment_notes" id="inv-payment-notes" class="form-input" placeholder="Contoh: Transfer via M-BCA jam 14:30" value="{{ old('payment_notes', $tx?->payment_notes) }}">
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-full" style="justify-content:center;font-size:15px;padding:14px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    {{ $hasProof ? 'Unggah Ulang Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
function toggleInvPayment(type) {
    const cardMidtrans = document.getElementById('inv-card-midtrans');
    const cardManual = document.getElementById('inv-card-manual');
    const secMidtrans = document.getElementById('inv-sec-midtrans');
    const secManual = document.getElementById('inv-sec-manual');

    if (type === 'manual_transfer') {
        if (cardMidtrans) {
            cardMidtrans.style.border = '1px solid var(--border)';
            cardMidtrans.style.background = '#fff';
        }
        if (cardManual) {
            cardManual.style.border = '2px solid var(--primary)';
            cardManual.style.background = 'rgba(67,97,238,0.06)';
        }
        if (secMidtrans) secMidtrans.style.display = 'none';
        if (secManual) secManual.style.display = 'flex';
    } else {
        if (cardMidtrans) {
            cardMidtrans.style.border = '2px solid var(--primary)';
            cardMidtrans.style.background = 'rgba(67,97,238,0.06)';
        }
        if (cardManual) {
            cardManual.style.border = '1px solid var(--border)';
            cardManual.style.background = '#fff';
        }
        if (secMidtrans) secMidtrans.style.display = 'flex';
        if (secManual) secManual.style.display = 'none';
    }
}

function copyInvAccNum(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor rekening ' + text + ' berhasil disalin!');
    }).catch(() => {
        alert('Nomor rekening: ' + text);
    });
}

function previewInvProof(input) {
    const wrap = document.getElementById('inv-proof-preview-wrap');
    const img = document.getElementById('inv-proof-preview-img');
    const nameEl = document.getElementById('inv-proof-file-name');

    if (!input.files || !input.files[0]) {
        if (wrap) wrap.style.display = 'none';
        return;
    }

    const file = input.files[0];
    if (nameEl) nameEl.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (img) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
            if (wrap) wrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        if (img) img.style.display = 'none';
        if (wrap) wrap.style.display = 'block';
    }
}
</script>
@endpush
