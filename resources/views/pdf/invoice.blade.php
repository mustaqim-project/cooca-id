<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #2b2d42; line-height: 1.5; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; font-size: 12px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-xl { font-size: 22px; font-weight: bold; color: #4361ee; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .mt-8 { margin-top: 28px; }
        .header-table { border: none; margin-bottom: 24px; }
        .header-table td { border: none; padding: 0; }
        .text-gray { color: #64748b; font-size: 12px; }
        .badge-paid { background-color: #def7ec; color: #03543f; padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .badge-pending { background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .bank-box { background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="55%" style="vertical-align: top;">
                <div class="text-xl">COOCA.ID</div>
                <div class="font-bold" style="color: #1e293b; margin-top: 4px;">PT COOCA TECHNOLOGIES INDONESIA</div>
                <div class="text-gray">Platform Enterprise SaaS & Infrastructure ERP Modern</div>
                <div class="text-gray">Email: support@cooca.id | Website: https://cooca.id</div>
            </td>
            <td width="45%" class="text-right" style="vertical-align: top;">
                <div style="font-size: 20px; font-weight: bold; color: #1e293b;">INVOICE</div>
                <div style="margin-top: 4px;">No: <strong style="color: #4361ee;">{{ $transaction->invoice_number }}</strong></div>
                <div class="text-gray">Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</div>
                <div style="margin-top: 6px;">
                    @if($transaction->status === 'paid')
                        <span class="badge-paid">LUNAS (PAID)</span>
                    @elseif($transaction->status === 'pending')
                        <span class="badge-pending">MENUNGGU PEMBAYARAN</span>
                    @else
                        <span style="color: #dc2626; font-weight: bold;">{{ strtoupper($transaction->status) }}</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="header-table" style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 6px;">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <strong style="color: #475569; font-size: 11px; text-transform: uppercase;">Ditagihkan Kepada:</strong><br>
                <div class="font-bold" style="font-size: 14px; margin-top: 4px;">{{ $transaction->customer->name ?? 'Customer' }}</div>
                <div>{{ $transaction->customer->email ?? '' }}</div>
                @if($transaction->customer->phone)
                    <div>Telp: {{ $transaction->customer->phone }}</div>
                @endif
                @if($transaction->customer->companyProfile?->company_name)
                    <div>Perusahaan: {{ $transaction->customer->companyProfile->company_name }}</div>
                @endif
            </td>
            <td width="50%" class="text-right" style="vertical-align: top;">
                <strong style="color: #475569; font-size: 11px; text-transform: uppercase;">Metode Pembayaran:</strong><br>
                <div class="font-bold" style="margin-top: 4px;">
                    @if($transaction->isManualTransfer())
                        Transfer Bank Manual
                    @else
                        Midtrans Payment Gateway
                    @endif
                </div>
                @if($transaction->paid_at)
                    <div class="text-gray">Dibayar pada: {{ $transaction->paid_at->format('d M Y H:i') }}</div>
                @endif
                @if($transaction->sender_name)
                    <div class="text-gray">Pengirim: {{ $transaction->sender_name }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Deskripsi Layanan</th>
                <th class="text-right" width="30%">Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="font-bold">
                        {{ $transaction->subscription?->subscriptionPlan?->product?->name ?? 'Layanan COOCA SaaS' }}
                    </div>
                    <div class="text-gray">
                        Paket: {{ $transaction->subscription?->subscriptionPlan?->name ?? 'Subscription Plan' }}
                        @if($transaction->subscription?->starts_at && $transaction->subscription?->ends_at)
                            ({{ $transaction->subscription->starts_at->format('d M Y') }} - {{ $transaction->subscription->ends_at->format('d M Y') }})
                        @endif
                    </div>
                </td>
                <td class="text-right font-bold">
                    Rp {{ number_format((float) ($transaction->gross_amount ?? $transaction->amount ?? 0), 0, ',', '.') }}
                </td>
            </tr>
            @if((float) $transaction->voucher_discount > 0)
            <tr>
                <td class="text-right"><strong>Diskon / Voucher</strong></td>
                <td class="text-right" style="color: #dc2626;">- Rp {{ number_format((float) $transaction->voucher_discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr style="background-color: #f8fafc;">
                <td class="text-right font-bold" style="font-size: 14px;">Total Tagihan (Net Amount)</td>
                <td class="text-right font-bold" style="font-size: 16px; color: #4361ee;">
                    Rp {{ number_format((float) ($transaction->net_amount ?? $transaction->amount ?? 0), 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Bank Transfer Destination for Unpaid Invoices --}}
    @if($transaction->status !== 'paid')
        @php
            $activeBanks = \App\Models\CompanyBankAccount::active()->ordered()->get();
        @endphp
        <div class="bank-box">
            <div class="font-bold" style="color: #1e293b; margin-bottom: 6px; font-size: 12px; text-transform: uppercase;">
                Petunjuk Pembayaran Transfer Bank Resmi:
            </div>
            <div class="text-gray" style="margin-bottom: 8px;">
                Silakan melakukan transfer tepat sesuai total nominal di atas ke salah satu rekening resmi PT COOCA TECHNOLOGIES INDONESIA:
            </div>
            <table class="header-table" style="margin-bottom: 0;">
                @forelse($activeBanks as $bank)
                    <tr>
                        <td width="35%"><strong>{{ $bank->bank_name }}</strong></td>
                        <td width="35%"><code style="font-size: 13px; font-weight: bold; color: #4361ee;">{{ $bank->account_number }}</code></td>
                        <td width="30%">a/n {{ $bank->account_holder }}</td>
                    </tr>
                @empty
                    <tr>
                        <td><strong>Bank Central Asia (BCA)</strong>: 8830-8899-8800 a/n PT COOCA TECHNOLOGIES INDONESIA</td>
                    </tr>
                @endforelse
            </table>
            <div class="text-gray" style="margin-top: 8px; font-style: italic;">
                *Setelah melakukan transfer, silakan login ke dashboard COOCA.ID untuk mengunggah foto/file bukti transfer.
            </div>
        </div>
    @endif

    <div class="mt-8 text-gray" style="text-align: center; border-top: 1px solid #e2e8f0; padding-top: 16px;">
        Dokumen ini dibuat otomatis oleh sistem penagihan elektronik COOCA.ID dan sah tanpa tanda tangan basah.<br>
        Butuh bantuan? Hubungi WhatsApp: +62 821-1446-8467 atau Email: support@cooca.id
    </div>
</body>
</html>
