<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Langganan - COOCA.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #f8f9fa;
            --text-dark: #2b2d42;
            --text-muted: #8d99ae;
            --success: #4cc9f0;
            --border-color: #edf2f4;
            --bg-color: #f4f7fe;
            --card-bg: #ffffff;
            --radius: 12px;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --secondary: #1e293b;
                --text-dark: #f8fafc;
                --text-muted: #94a3b8;
                --border-color: rgba(255, 255, 255, 0.08);
                --bg-color: #030712;
                --card-bg: #111827;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .checkout-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(43, 45, 66, 0.05);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }

        .checkout-header {
            background: var(--primary);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .checkout-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .checkout-header p {
            margin: 10px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .checkout-body {
            padding: 30px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .summary-item.total {
            border-top: 1px dashed var(--border-color);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .summary-label {
            color: var(--text-muted);
        }

        .summary-value {
            font-weight: 600;
        }

        .btn-pay {
            display: block;
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            margin-top: 30px;
            transition: all 0.3s;
        }

        .btn-pay:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .btn-pay:disabled {
            background: var(--text-muted);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .user-info {
            background: var(--secondary);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .user-info p {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="checkout-container">
        <div class="checkout-header">
            <h1>COOCA.ID</h1>
            <p>Selesaikan Pembayaran Langganan Anda</p>
        </div>

        <div class="checkout-body">
            <div class="user-info">
                <p><strong>Nama:</strong> {{ $customer->name }}</p>
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Perusahaan:</strong> {{ $customer->companyProfile->company_name ?? '-' }}</p>
            </div>

            <div class="summary">
                <div class="summary-item">
                    <span class="summary-label">Produk</span>
                    <span class="summary-value">{{ $plan->product->name }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Paket</span>
                    <span class="summary-value">{{ $plan->name }} ({{ $plan->duration_months }} Bulan)</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Harga Dasar</span>
                    <span class="summary-value">Rp {{ number_format($price, 0, ',', '.') }}</span>
                </div>

                @if (($discountAmount ?? 0) > 0)
                    <div class="summary-item">
                        <span class="summary-label">Diskon Paket ({{ $plan->discount_percent }}%)</span>
                        <span class="summary-value" style="color:#ef233c;">- Rp
                            {{ number_format($discountAmount ?? 0, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="summary-item total">
                    <span class="summary-label">Total Pembayaran</span>
                    <span class="summary-value">Rp {{ number_format($netAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            @if ($snapToken)
                <button id="pay-button" class="btn-pay"><i class="fa-solid fa-lock"></i> Bayar Sekarang via Midtrans</button>
            @elseif($pendingTransaction && $pendingTransaction->midtrans_order_id)
                <button id="pay-pending-button" class="btn-pay"><i class="fa-solid fa-clock"></i> Lanjutkan Pembayaran (Pending)</button>
            @else
                <button class="btn-pay" disabled>Gagal Memuat Pembayaran</button>
            @endif

            @php
                $dirBanks = \App\Models\CompanyBankAccount::active()->ordered()->get();
            @endphp

            @if($dirBanks->count() > 0)
                <div style="margin-top: 24px; border-top: 1px dashed var(--border-color); padding-top: 20px;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-building-columns" style="color: var(--primary);"></i> Pilihan Transfer Bank Manual
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($dirBanks as $dbk)
                            <div style="background: var(--secondary); border: 1px solid var(--border-color); border-radius: 8px; padding: 10px 12px; font-size: 13px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong style="color: var(--primary);">{{ $dbk->bank_name }}</strong>
                                    @if($dbk->is_primary)
                                        <span style="background: #fef3c7; color: #92400e; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px;">Utama</span>
                                    @endif
                                </div>
                                <div style="font-family: monospace; font-weight: 700; font-size: 14px; margin: 4px 0; color: var(--text-dark);">
                                    {{ $dbk->account_number }}
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    a/n {{ $dbk->account_holder }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px; text-align: center;">
                        Setelah transfer, silakan <a href="{{ route('customer.login') }}" style="color: var(--primary); font-weight: 600;">login ke Dashboard</a> untuk mengunggah bukti bayar.
                    </div>
                </div>
            @endif
        </div>
    </div>

    @php
        $integration = \App\Models\ApiIntegration::where('provider', 'midtrans')->where('is_active', true)->first();
        $isSandbox = true;
        $clientKey = config('services.midtrans.client_key');
        if ($integration && !empty($integration->config)) {
            $isSandbox = filter_var($integration->config['sandbox'] ?? true, FILTER_VALIDATE_BOOLEAN);
            $clientKey = $integration->config['client_key'] ?? $clientKey;
        }
        $midtransJsUrl = $isSandbox
            ? 'https://app.sandbox.midtrans.com/snap/snap.js'
            : 'https://app.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $midtransJsUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        @if ($snapToken)
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function() {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        window.location.href = "{{ route('customer.payments.success') }}";
                    },
                    onPending: function(result) {
                        window.location.href = "{{ route('customer.payments.pending') }}";
                    },
                    onError: function(result) {
                        window.location.href = "{{ route('customer.payments.failed') }}";
                    },
                    onClose: function() {
                        alert('Anda menutup jendela pembayaran sebelum menyelesaikannya.');
                    }
                });
            });
        @elseif ($pendingTransaction)
            var payPendingButton = document.getElementById('pay-pending-button');
            payPendingButton.addEventListener('click', function() {
                // If it is pending, ideally we should just query Midtrans status or redirect to invoice
                // For simplicity on direct link, we'll inform them it's pending.
                alert(
                    'Transaksi ini sedang berstatus PENDING. Silakan login ke Dashboard untuk melihat rincian pembayaran.');
                window.location.href = "{{ route('customer.login') }}";
            });
        @endif
    </script>
</body>

</html>
