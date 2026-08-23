<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pembayaran Langganan — {{ $siteName }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 24px;
            color: #1e293b;
        }
        .card {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            padding: 24px 28px;
            color: #ffffff;
            text-align: center;
        }
        .badge-success {
            background: #10B981;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .body {
            padding: 28px;
        }
        .amount-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            margin-bottom: 24px;
        }
        .amount-val {
            font-size: 24px;
            font-weight: 800;
            color: #15803d;
            margin-top: 4px;
        }
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .data-table td {
            padding: 7px 0;
            border-bottom: 1px dashed #f1f5f9;
        }
        .label {
            color: #64748b;
            width: 160px;
            font-weight: 600;
        }
        .value {
            font-weight: 700;
            color: #0f172a;
        }
        .btn-action {
            display: inline-block;
            background: #2563eb;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            margin-top: 24px;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 16px 28px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2 style="margin:0; font-size:18px;">🔔 Notifikasi Pembayaran Langganan Masuk</h2>
            <div class="badge-success">Pembayaran Berhasil / Dikonfirmasi</div>
        </div>

        <div class="body">
            <div class="amount-box">
                <div style="font-size: 12px; color: #166534; font-weight: 600;">TOTAL PEMBAYARAN DITERIMA</div>
                <div class="amount-val">Rp {{ number_format((float) ($transaction->net_amount ?? $transaction->gross_amount), 0, ',', '.') }}</div>
                <div style="font-size: 11px; color: #4b5563; margin-top: 4px;">
                    Metode: <strong>{{ strtoupper($transaction->payment_method ?? $transaction->payment_gateway ?? 'Online Payment') }}</strong>
                </div>
            </div>

            <div class="section-title">👤 Informasi Pelanggan</div>
            <table class="data-table">
                <tr>
                    <td class="label">Nama Lengkap:</td>
                    <td class="value">{{ $customer?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td class="value"><a href="mailto:{{ $customer?->email }}" style="color:#2563eb;">{{ $customer?->email ?? '—' }}</a></td>
                </tr>
                <tr>
                    <td class="label">No. WhatsApp / HP:</td>
                    <td class="value">
                        @if($customer?->phone)
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $customer->phone) }}" target="_blank" style="color:#16a34a; font-weight:700;">
                                +{{ $customer->phone }}
                            </a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @if($customer?->companyProfile?->company_name)
                <tr>
                    <td class="label">Perusahaan:</td>
                    <td class="value">{{ $customer->companyProfile->company_name }}</td>
                </tr>
                @endif
            </table>

            <div class="section-title">📦 Rincian Produk & Langganan</div>
            <table class="data-table">
                <tr>
                    <td class="label">Produk:</td>
                    <td class="value">{{ $product?->name ?? 'Langganan SaaS' }}</td>
                </tr>
                <tr>
                    <td class="label">Paket Langganan:</td>
                    <td class="value">{{ $plan?->name ?? 'Standar' }} ({{ $plan?->duration_months ?? 1 }} Bulan)</td>
                </tr>
                <tr>
                    <td class="label">Domain / Subdomain:</td>
                    <td class="value" style="color:#0284c7;">{{ $license?->domain ?? $subscription?->domain ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">No. Invoice:</td>
                    <td class="value">{{ $transaction->invoice_number ?? $transaction->invoice?->invoice_number ?? ('#' . $transaction->id) }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu Transaksi:</td>
                    <td class="value">{{ $transaction->paid_at ? $transaction->paid_at->translatedFormat('d M Y, H:i:s') . ' WIB' : now()->translatedFormat('d M Y, H:i:s') . ' WIB' }}</td>
                </tr>
            </table>

            @if($transaction->payment_proof)
            <div class="section-title" style="color:#b45309; border-color:#fef3c7;">📸 Bukti Pembayaran / Struk Transfer</div>
            <table class="data-table">
                @if($transaction->sender_name)
                <tr>
                    <td class="label">Nama Pemilik Rekening:</td>
                    <td class="value">{{ $transaction->sender_name }}</td>
                </tr>
                @endif
                @if($transaction->payment_notes)
                <tr>
                    <td class="label">Catatan Pengirim:</td>
                    <td class="value">{{ $transaction->payment_notes }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Waktu Upload:</td>
                    <td class="value">{{ $transaction->payment_proof_uploaded_at ? $transaction->payment_proof_uploaded_at->translatedFormat('d M Y, H:i:s') . ' WIB' : '—' }}</td>
                </tr>
                <tr>
                    <td class="label">File Bukti:</td>
                    <td class="value">
                        <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" style="display:inline-block; background:#0284c7; color:#ffffff; padding:6px 12px; border-radius:6px; text-decoration:none; font-size:12px; font-weight:700;">
                            Lihat / Unduh Bukti Transfer &rarr;
                        </a>
                    </td>
                </tr>
            </table>

            @php
                $ext = strtolower(pathinfo($transaction->payment_proof, PATHINFO_EXTENSION));
            @endphp
            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
            <div style="margin-top:12px; text-align:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
                <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Bukti Transfer" style="max-width:100%; max-height:350px; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            </div>
            @endif
            @endif

            <div style="text-align: center;">
                <a href="{{ route('admin.payments.index') }}" class="btn-action">Buka Menu Verifikasi Pembayaran Admin</a>
            </div>
        </div>

        <div class="footer">
            Email ini dikirim otomatis ke alamat tim administrator <strong>{{ $siteName }}</strong>:<br>
            <code>agungmustaqim15@gmail.com</code> & <code>cooca.idn@gmail.com</code>
        </div>
    </div>
</body>
</html>
