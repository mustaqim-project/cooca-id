@extends('layouts.customer')
@section('title', 'Renew Subscription — Checkout')
@section('breadcrumb')
    <a href="{{ route('customer.subscriptions.index') }}" class="crumb-link">Subscriptions</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('customer.subscriptions.show', $subscription->id) }}" class="crumb-link">Details</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Checkout</span>
@endsection

@section('content')
    @php
        $prod = $subscription->subscriptionPlan?->product ?? $subscription->license?->product;
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fa-solid fa-credit-card" style="color:var(--primary);margin-right:10px;"></i>
                Renewal Checkout
            </h1>
            <p class="page-subtitle">Bayar untuk memperpanjang langganan Anda.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('customer.subscriptions.show', $subscription->id) }}" class="btn btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
    @endif

    @if ($pendingTransaction)
        <div class="alert alert-warning mb-4">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <strong>Tagihan Pending Ditemukan:</strong> Terdapat transaksi renewal yang belum dibayar
            (<code>{{ $pendingTransaction->invoice_number }}</code>).
            Klik <strong>Bayar Sekarang</strong> untuk menyelesaikan pembayaran atau buat transaksi baru.
        </div>
    @endif

    <div class="grid-31" style="align-items:start;">

        {{-- Left: Product + Plan Summary --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Product Card --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Produk yang Diperpanjang</div>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        @if ($prod?->logo)
                            <img src="{{ asset($prod->logo) }}" alt="{{ $prod->name }}"
                                style="width:64px;height:64px;border-radius:12px;object-fit:contain;border:1px solid var(--border);padding:6px;">
                        @else
                            <div class="product-logo-placeholder"
                                style="width:64px;height:64px;font-size:26px;border-radius:12px;flex-shrink:0;">
                                {{ strtoupper(substr($prod?->name ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-xl">{{ $prod?->name ?? 'Subscription' }}</div>
                            <div class="text-sm text-muted">{{ $plan?->name ?? 'Plan' }}
                                @if ($plan?->duration_months)
                                    · {{ $plan->duration_months }} Bulan
                                @endif
                            </div>
                            <div class="mt-1">
                                @if ($subscription->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($subscription->status === 'expired')
                                    <span class="badge badge-danger">Expired</span>
                                @else
                                    <span class="badge badge-muted">{{ ucfirst($subscription->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="divider mt-4 mb-3"></div>

                    <div class="stats-row">
                        <span class="text-sm text-muted">Expired Saat Ini</span>
                        <span class="font-bold text-sm">
                            {{ $subscription->expires_at ? $subscription->expires_at->format('d M Y') : 'Belum Aktif' }}
                        </span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Akan Diperpanjang</span>
                        <span class="font-bold text-sm" style="color:var(--success);">
                            +{{ $plan?->duration_months ?? 1 }} bulan
                        </span>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Expire Setelah Pembayaran</span>
                        <span class="font-bold text-sm" style="color:var(--primary);">
                            @if ($subscription->expires_at)
                                {{ $subscription->expires_at->addMonths($plan?->duration_months ?? 1)->format('d M Y') }}
                            @else
                                {{ now()->addMonths($plan?->duration_months ?? 1)->format('d M Y') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Plan Features if any --}}
            @if (!empty($plan?->product?->features))
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Fitur yang Didapatkan</div>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                        @foreach (collect($plan->product->features)->take(6) as $feature)
                            <div class="flex items-center gap-2 text-sm">
                                <i class="{{ is_array($feature) ? $feature['icon'] ?? 'fa-solid fa-check-circle' : 'fa-solid fa-check-circle' }}"
                                    style="color:var(--success);font-size:13px;flex-shrink:0;"></i>
                                <span>{{ is_array($feature) ? $feature['title'] ?? ($feature['name'] ?? '') : (is_object($feature) ? $feature->title ?? ($feature->name ?? '') : $feature) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Pricing + Payment --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Price Summary --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Ringkasan Pembayaran</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">

                    <div class="stats-row">
                        <span class="text-sm text-muted">Harga Plan</span>
                        <span class="font-bold">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    </div>

                    @if (($discountPercent ?? 0) > 0)
                        <div class="stats-row">
                            <span class="text-sm" style="color:var(--success);">Diskon ({{ $discountPercent }}%)</span>
                            <span class="font-bold" style="color:var(--success);">- Rp
                                {{ number_format($discountAmount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="divider"></div>

                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div class="text-sm font-bold">Punya Kode Voucher?</div>
                        <div style="display:flex;gap:8px;">
                            <input type="text" id="voucher-code" class="form-input" placeholder="Masukkan kode promo..."
                                style="flex:1;">
                            <button type="button" id="btn-apply-voucher" class="btn btn-primary"
                                style="padding:8px 16px;">Gunakan</button>
                        </div>
                        <div id="voucher-message" class="text-xs" style="display:none;margin-top:4px;"></div>
                    </div>

                    <div id="voucher-discount-row" class="stats-row" style="display:none;">
                        <span class="text-sm" style="color:var(--success);">Voucher (<span
                                id="applied-voucher-code"></span>)</span>
                        <span class="font-bold" style="color:var(--success);">- Rp <span
                                id="voucher-discount-amount">0</span></span>
                    </div>

                    <div class="divider"></div>

                    <div class="stats-row" style="padding:12px;background:var(--bg);border-radius:var(--radius);">
                        <span class="font-bold text-base">Total Bayar</span>
                        <span class="font-bold text-xl" style="color:var(--primary);">
                            Rp <span id="display-net-amount">{{ number_format($netAmount, 0, ',', '.') }}</span>
                        </span>
                    </div                    <div class="text-xs text-muted" style="text-align:center;line-height:1.5;">
                        <i class="fa-solid fa-shield-halved" style="color:var(--success);"></i>
                        Pilih metode pembayaran aman melalui <strong>Midtrans</strong> atau <strong>Transfer Bank Manual</strong>
                    </div>
                </div>
            </div>

            {{-- Payment Action --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Pilih Metode Pembayaran</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                    @if (isset($snapToken) && $snapToken)
                        {{-- Snap token ready: open Midtrans popup --}}
                        <div style="text-align:center;padding:16px 0 8px;">
                            <i class="fa-solid fa-circle-check"
                                style="color:var(--success);font-size:32px;margin-bottom:8px;display:block;"></i>
                            <div class="font-bold text-sm">Transaksi Midtrans Dibuat!</div>
                            <div class="text-xs text-muted mt-1">Klik tombol di bawah untuk membuka popup pembayaran.</div>
                        </div>

                        <button id="pay-button" class="btn btn-primary w-full"
                            style="justify-content:center;font-size:15px;padding:14px;">
                            <i class="fa-solid fa-lock"></i>
                            Bayar Sekarang via Midtrans — Rp {{ number_format($netAmount, 0, ',', '.') }}
                        </button>

                        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                        <script>
                            document.getElementById('pay-button').onclick = function() {
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
                                    onClose: function() {
                                        // User closed without paying
                                    }
                                });
                            };
                        </script>

                        @if (isset($snapUrl) && $snapUrl)
                            <a href="{{ $snapUrl }}" class="btn btn-outline w-full"
                                style="justify-content:center;font-size:13px;" target="_blank">
                                <i class="fa-solid fa-external-link-alt"></i>
                                Buka di Halaman Baru
                            </a>
                        @endif
                    @else
                        {{-- Payment Method Selection Tabs / Radio Cards --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <label class="payment-method-card" id="card-midtrans" style="border:2px solid var(--primary);background:rgba(67,97,238,0.05);padding:14px;border-radius:var(--radius);cursor:pointer;display:flex;flex-direction:column;gap:6px;transition:all 0.2s;">
                                <input type="radio" name="select_payment_type" value="midtrans" checked onchange="togglePaymentMethod('midtrans')" style="display:none;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-bolt" style="color:var(--primary);font-size:16px;"></i>
                                    <strong style="font-size:13px;">Payment Gateway</strong>
                                </div>
                                <span class="text-xs text-muted">Midtrans (QRIS, VA Bank, E-Wallet, Kartu)</span>
                                <span class="badge badge-success" style="align-self:flex-start;font-size:10px;margin-top:4px;">Otomatis Instan</span>
                            </label>

                            <label class="payment-method-card" id="card-manual" style="border:1px solid var(--border);background:var(--bg);padding:14px;border-radius:var(--radius);cursor:pointer;display:flex;flex-direction:column;gap:6px;transition:all 0.2s;">
                                <input type="radio" name="select_payment_type" value="manual_transfer" onchange="togglePaymentMethod('manual_transfer')" style="display:none;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-building-columns" style="color:#6c757d;font-size:16px;"></i>
                                    <strong style="font-size:13px;">Transfer Manual</strong>
                                </div>
                                <span class="text-xs text-muted">Transfer ke Rekening Resmi PT COOCA</span>
                                <span class="badge badge-warning" style="align-self:flex-start;font-size:10px;margin-top:4px;">Upload Bukti Bayar</span>
                            </label>
                        </div>

                        {{-- Section 1: Midtrans Gateway Form --}}
                        <div id="section-midtrans" style="display:flex;flex-direction:column;gap:12px;">
                            <div class="flex items-center gap-2 p-3"
                                style="border:1px solid var(--border);border-radius:var(--radius);background:var(--bg);">
                                <i class="fa-solid fa-circle-check"
                                    style="color:var(--primary);font-size:18px;flex-shrink:0;"></i>
                                <div>
                                    <div class="text-sm font-bold">Midtrans Payment Gateway</div>
                                    <div class="text-xs text-muted">BCA, BNI, BRI, Mandiri VA, Permata, GoPay, OVO, ShopeePay, QRIS, Kartu Kredit.</div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('customer.subscriptions.checkout.process', $subscription->id) }}">
                                @csrf
                                <input type="hidden" name="payment_type" value="midtrans">
                                <input type="hidden" name="voucher_code" id="hidden-voucher-code" value="">
                                <button type="submit" class="btn btn-primary w-full" id="btn-pay-submit"
                                    style="justify-content:center;font-size:15px;padding:14px;">
                                    <i class="fa-solid fa-lock"></i>
                                    Bayar via Midtrans — Rp <span id="btn-net-amount">{{ number_format($netAmount, 0, ',', '.') }}</span>
                                </button>
                            </form>
                        </div>

                        {{-- Section 2: Manual Bank Transfer Form with Required Proof Upload --}}
                        <div id="section-manual" style="display:none;flex-direction:column;gap:14px;">
                            @php
                                $companyBanks = \App\Models\CompanyBankAccount::active()->ordered()->get();
                                $bName = $bankSettings['bank_name'] ?? 'Bank Central Asia (BCA)';
                                $bNumber = $bankSettings['account_number'] ?? '8830-8899-8800';
                                $bHolder = $bankSettings['account_name'] ?? 'PT COOCA TECHNOLOGIES INDONESIA';
                                $bInst = $bankSettings['instructions'] ?? 'Silakan transfer sesuai jumlah total tagihan.';
                            @endphp

                            <div style="display:flex;flex-direction:column;gap:10px;">
                                <div class="text-xs font-bold uppercase text-muted" style="letter-spacing:0.5px;">
                                    <i class="fa-solid fa-building-columns" style="color:var(--primary);margin-right:4px;"></i> Pilihan Rekening Tujuan Transfer
                                </div>

                                @if($companyBanks->count() > 0)
                                    @foreach($companyBanks as $cBank)
                                        <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:14px;box-shadow:0 2px 6px rgba(0,0,0,0.02);position:relative;">
                                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                                <div style="display:flex;align-items:center;gap:8px;">
                                                    @if($cBank->logo_url)
                                                        <img src="{{ $cBank->logo_url }}" alt="{{ $cBank->bank_name }}" style="height:22px;max-width:50px;object-fit:contain;">
                                                    @else
                                                        <div style="width:24px;height:24px;border-radius:6px;background:{{ $cBank->badge_color }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:bold;">
                                                            {{ substr($cBank->bank_name, 0, 3) }}
                                                        </div>
                                                    @endif
                                                    <span class="font-bold text-sm">{{ $cBank->bank_name }}</span>
                                                </div>
                                                @if($cBank->is_primary)
                                                    <span class="badge badge-warning" style="font-size:10px;"><i class="fa-solid fa-star"></i> Utama</span>
                                                @endif
                                            </div>

                                            <div style="display:flex;align-items:center;justify-content:space-between;background:var(--bg);padding:8px 12px;border-radius:8px;border:1px dashed var(--primary);margin:6px 0;">
                                                <div>
                                                    <div class="text-xs text-muted">Nomor Rekening:</div>
                                                    <div class="font-mono font-bold text-base">{{ $cBank->account_number }}</div>
                                                </div>
                                                <button type="button" class="btn btn-ghost btn-sm" onclick="copyAccountNumber('{{ $cBank->account_number }}')" title="Salin No. Rekening">
                                                    <i class="fa-solid fa-copy"></i> Salin
                                                </button>
                                            </div>

                                            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                                                <div class="text-xs text-muted">
                                                    a/n <strong style="color:var(--text);">{{ $cBank->account_holder }}</strong>
                                                    @if($cBank->branch) <span style="font-size:11px;">({{ $cBank->branch }})</span> @endif
                                                </div>
                                                @if($cBank->qr_code_url)
                                                    <a href="{{ $cBank->qr_code_url }}" target="_blank" class="badge badge-success" style="font-size:10px;cursor:pointer;text-decoration:none;">
                                                        <i class="fa-solid fa-qrcode"></i> Lihat QRIS
                                                    </a>
                                                @endif
                                            </div>

                                            @if($cBank->instructions)
                                                <div class="text-xs text-muted mt-2" style="font-style:italic;line-height:1.3;background:rgba(67,97,238,0.03);padding:6px 8px;border-radius:6px;">
                                                    {{ $cBank->instructions }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div style="background:linear-gradient(135deg, rgba(67,97,238,0.08), rgba(76,201,240,0.08));border:1px solid var(--border);border-radius:var(--radius);padding:16px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                                            <span class="text-xs font-bold uppercase text-muted">Rekening Tujuan Transfer</span>
                                            <span class="badge badge-primary">{{ $bName }}</span>
                                        </div>
                                        <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;padding:10px 14px;border-radius:8px;border:1px dashed var(--primary);margin-bottom:8px;">
                                            <div>
                                                <div class="text-xs text-muted">Nomor Rekening:</div>
                                                <div class="font-mono font-bold text-lg">{{ $bNumber }}</div>
                                            </div>
                                            <button type="button" class="btn btn-ghost btn-sm" onclick="copyAccountNumber('{{ $bNumber }}')" title="Salin No. Rekening">
                                                <i class="fa-solid fa-copy"></i> Salin
                                            </button>
                                        </div>
                                        <div class="text-xs text-muted">
                                            Atas Nama: <strong style="color:var(--text);">{{ $bHolder }}</strong>
                                        </div>
                                        <div class="text-xs text-muted mt-2" style="font-style:italic;line-height:1.4;">
                                            {{ $bInst }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('customer.subscriptions.checkout.process', $subscription->id) }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:12px;">
                                @csrf
                                <input type="hidden" name="payment_type" value="manual_transfer">
                                <input type="hidden" name="voucher_code" id="hidden-voucher-code-manual" value="">

                                <div>
                                    <label class="form-label text-xs font-bold" for="manual-sender-name">
                                        Nama Pemilik Rekening Pengirim <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="sender_name" id="manual-sender-name" class="form-input" placeholder="Contoh: Budi Santoso" required>
                                </div>

                                <div>
                                    <label class="form-label text-xs font-bold" for="manual-payment-proof">
                                        Upload Bukti Bayar / Struk Transfer <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="payment_proof" id="manual-payment-proof" class="form-input" accept="image/jpeg,image/png,image/webp,application/pdf" required onchange="previewProofFile(this)">
                                    <div class="text-xs text-muted mt-1">Format didukung: JPG, PNG, WEBP, PDF (Maksimal 5MB).</div>
                                    <div id="proof-preview-wrap" style="display:none;margin-top:10px;text-align:center;">
                                        <img id="proof-preview-img" src="" alt="Preview Bukti" style="max-height:160px;border-radius:8px;border:1px solid var(--border);margin:0 auto;object-fit:contain;">
                                        <div id="proof-file-name" class="text-xs text-muted mt-1"></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label text-xs font-bold" for="manual-payment-notes">
                                        Catatan Pembayaran (Opsional)
                                    </label>
                                    <input type="text" name="payment_notes" id="manual-payment-notes" class="form-input" placeholder="Contoh: Transfer via M-BCA jam 14:30">
                                </div>

                                <button type="submit" class="btn btn-success w-full" style="justify-content:center;font-size:15px;padding:14px;margin-top:4px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    Kirim Bukti Bayar — Rp <span id="btn-net-amount-manual">{{ number_format($netAmount, 0, ',', '.') }}</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="text-xs text-muted" style="text-align:center;">
                        Dengan melanjutkan, Anda menyetujui <span style="color:var(--primary);">Syarat & Ketentuan</span>
                        layanan kami.
                    </div>
                </div>
            </div>

            {{-- Info box --}}
            <div
                style="border:1px solid var(--border);border-radius:var(--radius);padding:14px;display:flex;flex-direction:column;gap:8px;">
                <div class="text-sm font-bold"><i class="fa-solid fa-circle-info"
                        style="color:var(--primary);margin-right:6px;"></i>Info Pembayaran</div>
                <div class="text-xs text-muted" style="line-height:1.6;">
                    • <strong>Midtrans:</strong> Konfirmasi otomatis dalam 1–5 menit.<br>
                    • <strong>Transfer Bank Manual:</strong> Verifikasi oleh tim Finance dalam jam kerja.<br>
                    • Invoice dan notifikasi konfirmasi akan dikirim ke email & WhatsApp Anda.<br>
                    • Hubungi support jika mengalami kendala transaksi.
                </div>
            </div>            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePaymentMethod(type) {
            const cardMidtrans = document.getElementById('card-midtrans');
            const cardManual = document.getElementById('card-manual');
            const secMidtrans = document.getElementById('section-midtrans');
            const secManual = document.getElementById('section-manual');

            if (type === 'manual_transfer') {
                if (cardMidtrans) {
                    cardMidtrans.style.border = '1px solid var(--border)';
                    cardMidtrans.style.background = 'var(--bg)';
                }
                if (cardManual) {
                    cardManual.style.border = '2px solid var(--primary)';
                    cardManual.style.background = 'rgba(67,97,238,0.05)';
                }
                if (secMidtrans) secMidtrans.style.display = 'none';
                if (secManual) secManual.style.display = 'flex';
            } else {
                if (cardMidtrans) {
                    cardMidtrans.style.border = '2px solid var(--primary)';
                    cardMidtrans.style.background = 'rgba(67,97,238,0.05)';
                }
                if (cardManual) {
                    cardManual.style.border = '1px solid var(--border)';
                    cardManual.style.background = 'var(--bg)';
                }
                if (secMidtrans) secMidtrans.style.display = 'flex';
                if (secManual) secManual.style.display = 'none';
            }
        }

        function copyAccountNumber(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor rekening ' + text + ' berhasil disalin!');
            }).catch(() => {
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Nomor rekening ' + text + ' berhasil disalin!');
            });
        }

        function previewProofFile(input) {
            const wrap = document.getElementById('proof-preview-wrap');
            const img = document.getElementById('proof-preview-img');
            const nameEl = document.getElementById('proof-file-name');

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

        document.addEventListener('DOMContentLoaded', function() {
            const btnApply = document.getElementById('btn-apply-voucher');
            if (!btnApply) return;

            btnApply.addEventListener('click', function() {
                const code = document.getElementById('voucher-code').value;
                const messageEl = document.getElementById('voucher-message');

                if (!code) {
                    messageEl.style.display = 'block';
                    messageEl.style.color = 'var(--danger)';
                    messageEl.textContent = 'Silakan masukkan kode voucher.';
                    return;
                }

                btnApply.disabled = true;
                btnApply.textContent = '...';

                fetch("{{ route('customer.subscriptions.apply-voucher', $subscription->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            voucher_code: code
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        messageEl.style.display = 'block';
                        if (data.success) {
                            messageEl.style.color = 'var(--success)';
                            messageEl.textContent = data.message;

                            document.getElementById('voucher-discount-row').style.display = 'flex';
                            document.getElementById('applied-voucher-code').textContent = data.voucher_code;
                            document.getElementById('voucher-discount-amount').textContent = new Intl.NumberFormat('id-ID').format(data.discount);

                            const newTotalFormatted = new Intl.NumberFormat('id-ID').format(data.new_total);
                            document.getElementById('display-net-amount').textContent = newTotalFormatted;

                            const btnNetAmount = document.getElementById('btn-net-amount');
                            if (btnNetAmount) {
                                btnNetAmount.textContent = newTotalFormatted;
                            }

                            const btnNetAmountManual = document.getElementById('btn-net-amount-manual');
                            if (btnNetAmountManual) {
                                btnNetAmountManual.textContent = newTotalFormatted;
                            }

                            document.getElementById('hidden-voucher-code').value = data.voucher_code;
                            const hiddenManual = document.getElementById('hidden-voucher-code-manual');
                            if (hiddenManual) hiddenManual.value = data.voucher_code;
                        } else {
                            messageEl.style.color = 'var(--danger)';
                            messageEl.textContent = data.message;
                        }
                    })
                    .catch(error => {
                        messageEl.style.display = 'block';
                        messageEl.style.color = 'var(--danger)';
                        messageEl.textContent = 'Terjadi kesalahan sistem.';
                    })
                    .finally(() => {
                        btnApply.disabled = false;
                        btnApply.textContent = 'Gunakan';
                    });
            });
        });
    </script>
@endsection
