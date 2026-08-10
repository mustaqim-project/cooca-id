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
                    </div>

                    <div class="text-xs text-muted" style="text-align:center;line-height:1.5;">
                        <i class="fa-solid fa-shield-halved" style="color:var(--success);"></i>
                        Pembayaran aman melalui <strong>Midtrans</strong> · Transfer Bank, QRIS, e-Wallet
                    </div>
                </div>
            </div>

            {{-- Payment Action --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Metode Pembayaran</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">

                    @if (isset($snapToken) && $snapToken)
                        {{-- Snap token ready: open Midtrans popup --}}
                        <div style="text-align:center;padding:16px 0 8px;">
                            <i class="fa-solid fa-circle-check"
                                style="color:var(--success);font-size:32px;margin-bottom:8px;display:block;"></i>
                            <div class="font-bold text-sm">Transaksi dibuat!</div>
                            <div class="text-xs text-muted mt-1">Klik tombol di bawah untuk membuka halaman pembayaran.
                            </div>
                        </div>

                        <button id="pay-button" class="btn btn-primary w-full"
                            style="justify-content:center;font-size:15px;padding:14px;">
                            <i class="fa-solid fa-lock"></i>
                            Bayar Sekarang — Rp {{ number_format($netAmount, 0, ',', '.') }}
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
                        {{-- Show "Proceed to Pay" form --}}
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <div class="flex items-center gap-2 p-3"
                                style="border:1px solid var(--border);border-radius:var(--radius);background:var(--bg);">
                                <i class="fa-solid fa-building-columns"
                                    style="color:var(--primary);font-size:18px;flex-shrink:0;"></i>
                                <div>
                                    <div class="text-sm font-bold">Transfer Bank / QRIS / e-Wallet</div>
                                    <div class="text-xs text-muted">BCA, BNI, BRI, Mandiri, GoPay, OVO, QRIS, dll.</div>
                                </div>
                            </div>
                        </div>

                        <form method="POST"
                            action="{{ route('customer.subscriptions.checkout.process', $subscription->id) }}">
                            @csrf
                            <input type="hidden" name="voucher_code" id="hidden-voucher-code" value="">
                            <button type="submit" class="btn btn-primary w-full" id="btn-pay-submit"
                                style="justify-content:center;font-size:15px;padding:14px;">
                                <i class="fa-solid fa-lock"></i>
                                Lanjutkan Pembayaran — Rp <span
                                    id="btn-net-amount">{{ number_format($netAmount, 0, ',', '.') }}</span>
                            </button>
                        </form>
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
                    • Setelah pembayaran dikonfirmasi, langganan Anda akan otomatis diperpanjang.<br>
                    • Proses konfirmasi biasanya membutuhkan waktu <strong>1–5 menit</strong>.<br>
                    • Invoice akan dikirim ke email Anda setelah pembayaran berhasil.<br>
                    • Hubungi support jika pembayaran tidak terkonfirmasi dalam 24 jam.
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
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
                            document.getElementById('applied-voucher-code').textContent = data
                                .voucher_code;
                            document.getElementById('voucher-discount-amount').textContent = new Intl
                                .NumberFormat('id-ID').format(data.discount);

                            const newTotalFormatted = new Intl.NumberFormat('id-ID').format(data
                                .new_total);
                            document.getElementById('display-net-amount').textContent =
                                newTotalFormatted;

                            const btnNetAmount = document.getElementById('btn-net-amount');
                            if (btnNetAmount) {
                                btnNetAmount.textContent = newTotalFormatted;
                            }

                            document.getElementById('hidden-voucher-code').value = data.voucher_code;
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
