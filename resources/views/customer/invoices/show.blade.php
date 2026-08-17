@extends('layouts.customer')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('breadcrumb')
    <a href="{{ route('customer.invoices.index') }}" class="crumb-link">Invoices</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">{{ $invoice->invoice_number }}</span>
@endsection
@section('content')
    @php
        $isPaid = $invoice->isPaid();
        $tx = $invoice->transaction;
        $hasProof = $tx && $tx->payment_proof;
        $isVerifying = !$isPaid && $hasProof && $tx->status === 'pending';
        $isRejected = !$isPaid && $tx && $tx->status === 'failed' && $tx->rejection_reason;
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="fa-solid fa-file-invoice"
                    style="color:var(--primary);margin-right:10px;"></i>Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="page-subtitle">Diterbitkan pada
                {{ $invoice->issued_at?->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('customer.invoices.download', $invoice->id) }}" class="btn btn-outline">
                <i class="fa-solid fa-download"></i> Download PDF
            </a>
            <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
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
                        @if ($isPaid)
                            <span class="badge badge-success" style="font-size:14px;padding:6px 14px;">PAID</span>
                        @elseif($isVerifying)
                            <span class="badge badge-warning" style="font-size:14px;padding:6px 14px;">
                                <i class="fa-solid fa-clock"></i> VERIFIKASI PEMBAYARAN
                            </span>
                        @elseif($invoice->status === 'overdue' || ($invoice->due_at && $invoice->due_at->isPast()))
                            <span class="badge badge-danger" style="font-size:14px;padding:6px 14px;">OVERDUE</span>
                        @else
                            <span class="badge badge-warning" style="font-size:14px;padding:6px 14px;">PENDING PAYMENT</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid-2 mb-6" style="background:var(--bg-secondary);border-radius:var(--radius);padding:18px;border:1px solid var(--border);">
                <div>
                    <div class="text-xs text-muted font-bold uppercase mb-1">Ditagihkan Kepada (Billed To)</div>
                    <div class="font-bold text-sm" style="color: var(--text);">
                        {{ auth('customer')->user()->business_name ?? auth('customer')->user()->name }}</div>
                    <div class="text-xs text-muted">{{ auth('customer')->user()->email }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-muted font-bold uppercase mb-1">Tanggal Tagihan</div>
                    <div class="text-xs">Diterbitkan: <strong>{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</strong></div>
                    <div class="text-xs">Jatuh Tempo: <strong style="color:var(--danger);">{{ $invoice->due_at?->format('d M Y') ?? '—' }}</strong></div>
                    @if($isPaid)
                        <div class="text-xs mt-1" style="color: var(--success); font-weight: 700;">
                            Lunas: {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y H:i') : ($tx?->paid_at ? $tx->paid_at->format('d M Y H:i') : '—') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Table --}}
            <table class="data-table mb-6">
                <thead>
                    <tr>
                        <th>Deskripsi Item</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="font-bold text-sm" style="color: var(--text);">
                                {{ $invoice->transaction?->subscription?->product?->name ?? 'COOCA SaaS Subscription' }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $invoice->transaction?->subscription?->subscriptionPlan?->name ?? 'Service Plan' }}
                            </div>
                        </td>
                        <td class="text-right font-bold text-base" style="color: var(--text);">
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-between items-center pt-4" style="border-top:2px solid var(--border);">
                <div class="text-sm font-bold" style="color: var(--text);">Total Tagihan:</div>
                <div class="text-2xl font-bold" style="color:var(--primary);">
                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                </div>
            </div>

            {{-- Condition A: Invoice is PAID --}}
            @if ($isPaid)
                <div class="divider my-6" style="height:1px;background:var(--border);"></div>
                <div class="card" style="border: 1px solid var(--success); background: var(--success-soft); box-shadow: none;">
                    <div class="card-body" style="padding: 24px;">
                        <div class="flex items-center gap-3">
                            <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--success); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <div class="font-bold text-base" style="color: var(--success);">Tagihan Ini Telah Lunas (PAID)</div>
                                <div class="text-xs text-muted" style="color: var(--text-2); margin-top: 2px;">
                                    Pembayaran telah dikonfirmasi dan layanan SaaS Anda telah aktif.
                                </div>
                            </div>
                        </div>

                        <div class="grid-2 mt-4 text-xs" style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px; gap: 12px;">
                            <div>
                                <span class="text-muted">Metode Pembayaran:</span>
                                <div class="font-bold mt-1" style="color: var(--text);">
                                    {{ $tx?->isManualTransfer() ? 'Transfer Bank Manual' : 'Midtrans Payment Gateway' }}
                                </div>
                            </div>
                            <div>
                                <span class="text-muted">Waktu Konfirmasi Lunas:</span>
                                <div class="font-bold mt-1 font-mono" style="color: var(--text);">
                                    {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, H:i') : ($tx?->paid_at ? $tx->paid_at->format('d M Y, H:i') : '—') }}
                                </div>
                            </div>
                            @if($tx?->sender_name)
                                <div>
                                    <span class="text-muted">Nama Pengirim Rekening:</span>
                                    <div class="font-bold mt-1" style="color: var(--text);">{{ $tx->sender_name }}</div>
                                </div>
                            @endif
                            @if($tx?->payment_proof)
                                <div>
                                    <span class="text-muted">Bukti Pembayaran:</span>
                                    <div class="mt-1">
                                        <a href="{{ $tx->payment_proof_url }}" target="_blank" class="btn btn-ghost btn-xs" style="color: var(--primary);">
                                            <i class="fa-solid fa-file-invoice"></i> Buka Bukti Bayar
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            {{-- Condition B: Invoice is UNPAID / PENDING --}}
            @else
                <div class="divider my-6" style="height:1px;background:var(--border);"></div>

                @if ($isRejected)
                    <div class="alert alert-danger mb-4">
                        <div class="font-bold"><i class="fa-solid fa-circle-xmark"></i> Bukti Pembayaran Sebelumnya Ditolak</div>
                        <div class="text-xs mt-1">Alasan: <strong>{{ $tx->rejection_reason }}</strong></div>
                        <div class="text-xs mt-1">Silakan lakukan transfer ulang atau unggah bukti transfer yang valid di bawah ini.</div>
                    </div>
                @elseif ($isVerifying)
                    <div class="alert alert-warning mb-4" style="border-radius:var(--radius);padding:16px;">
                        <div class="flex items-center justify-between" style="flex-wrap:wrap;gap:12px;">
                            <div>
                                <div class="font-bold text-sm"><i class="fa-solid fa-clock"></i> Bukti Transfer Sedang Diverifikasi</div>
                                <div class="text-xs mt-1">Pengirim: <strong>{{ $tx->sender_name ?? 'Pelanggan' }}</strong> · Diunggah pada: {{ $tx->payment_proof_uploaded_at?->format('d M Y H:i') ?? $tx->updated_at->format('d M Y H:i') }}</div>
                                <div class="text-xs mt-1 text-muted">Tim admin kami sedang memeriksa mutasi rekening. Transaksi akan otomatis aktif setelah diverifikasi.</div>
                            </div>
                            <div>
                                <a href="{{ $tx->payment_proof_url }}" target="_blank" class="btn btn-outline btn-sm">
                                    <i class="fa-solid fa-file-invoice"></i> Lihat Bukti Bayar
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card" style="border:1px solid var(--border);box-shadow:none;">
                    <div class="card-header" style="border-bottom:1px solid var(--border);">
                        <div class="card-title text-base font-bold">Pilih Cara Pembayaran Tagihan</div>
                    </div>
                    <div class="card-body" style="padding:20px;display:flex;flex-direction:column;gap:16px;">

                        {{-- Tab Switcher --}}
                        <div class="payment-methods-grid">
                            <label class="payment-method-card active" id="inv-card-midtrans" onclick="toggleInvPayment('midtrans')">
                                <input type="radio" name="inv_payment_type" value="midtrans" checked onchange="toggleInvPayment('midtrans')" style="display:none;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-bolt" style="color:var(--primary);font-size:16px;"></i>
                                    <strong style="font-size:13px;">Payment Gateway</strong>
                                </div>
                                <span class="text-xs text-muted">Midtrans (QRIS, VA Bank, E-Wallet)</span>
                                <span class="badge badge-success" style="align-self:flex-start;font-size:10px;margin-top:2px;">Otomatis Instan</span>
                            </label>

                            <label class="payment-method-card" id="inv-card-manual" onclick="toggleInvPayment('manual_transfer')">
                                <input type="radio" name="inv_payment_type" value="manual_transfer" onchange="toggleInvPayment('manual_transfer')" style="display:none;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-building-columns" style="color:var(--text-muted);font-size:16px;"></i>
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

                            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;">
                                <div class="text-xs text-muted font-bold uppercase mb-2">Rekening Tujuan Transfer:</div>
                                @if($cBanks->isNotEmpty())
                                    <div style="display:flex;flex-direction:column;gap:10px;">
                                        @foreach($cBanks as $b)
                                            <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;display:flex;justify-content:space-between;align-items:center;">
                                                <div>
                                                    <div class="font-bold text-sm" style="color:var(--text);">{{ $b->bank_name }}</div>
                                                    <div class="font-mono text-base font-bold text-primary" style="letter-spacing:1px;">{{ $b->account_number }}</div>
                                                    <div class="text-xs text-muted">a.n. {{ $b->account_holder }}</div>
                                                </div>
                                                <button type="button" class="btn btn-ghost btn-xs" onclick="navigator.clipboard.writeText('{{ $b->account_number }}');alert('Nomor rekening disalin!')">
                                                    <i class="fa-solid fa-copy"></i> Salin
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;">
                                        <div class="font-bold text-sm">{{ \App\Models\Setting::get('payment.bank_transfer.bank_name', 'BCA') }}</div>
                                        <div class="font-mono text-base font-bold text-primary">{{ \App\Models\Setting::get('payment.bank_transfer.account_number', '883088998800') }}</div>
                                        <div class="text-xs text-muted">a.n. {{ \App\Models\Setting::get('payment.bank_transfer.account_name', 'PT COOCA TECHNOLOGIES INDONESIA') }}</div>
                                    </div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('customer.subscriptions.checkout.process', $invoice->transaction?->subscription_id ?? $invoice->id) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="payment_type" value="manual_transfer">
                                <input type="hidden" name="transaction_id" value="{{ $invoice->transaction_id }}">

                                <div class="form-group mb-3">
                                    <label class="form-label text-xs font-bold uppercase">Nama Pemilik Rekening Pengirim <span class="text-danger">*</span></label>
                                    <input type="text" name="sender_name" class="form-input" required placeholder="Contoh: Budi Santoso" value="{{ old('sender_name', $tx?->sender_name) }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label text-xs font-bold uppercase">Unggah Bukti Transfer (JPG/PNG/PDF, maks 5MB) <span class="text-danger">*</span></label>
                                    <input type="file" name="payment_proof" class="form-input" required accept=".jpg,.jpeg,.png,.pdf" onchange="previewInvProof(this)">
                                    <div id="inv-proof-preview" style="display:none;margin-top:10px;">
                                        <img id="inv-preview-img" src="#" alt="Preview Bukti" style="max-height:180px;border-radius:6px;border:1px solid var(--border);">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-xs font-bold uppercase">Catatan Pembayaran (Opsional)</label>
                                    <textarea name="payment_notes" class="form-textarea" rows="2" placeholder="Catatan tambahan bila ada...">{{ old('payment_notes') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content:center;font-size:15px;padding:14px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Kirim Bukti Transfer
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

    if (!cardMidtrans || !cardManual || !secMidtrans || !secManual) return;

    if (type === 'midtrans') {
        cardMidtrans.classList.add('active');
        cardManual.classList.remove('active');
        secMidtrans.style.display = 'flex';
        secManual.style.display = 'none';
    } else {
        cardManual.classList.add('active');
        cardMidtrans.classList.remove('active');
        secMidtrans.style.display = 'none';
        secManual.style.display = 'flex';
    }
}

function previewInvProof(input) {
    const preview = document.getElementById('inv-proof-preview');
    const img = document.getElementById('inv-preview-img');
    if (!preview || !img) return;

    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
}
</script>
@endpush
