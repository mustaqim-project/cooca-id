<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-xl { font-size: 24px; font-weight: bold; }
        .mb-4 { margin-bottom: 16px; }
        .mt-8 { margin-top: 32px; }
        .header-table { border: none; }
        .header-table td { border: none; padding: 0; }
        .text-gray { color: #666; }
    </style>
</head>
<body>
    <table class="header-table mb-4">
        <tr>
            <td>
                <div class="text-xl">COOCA.ID</div>
                <div class="text-gray">Layanan SaaS Terbaik</div>
            </td>
            <td class="text-right">
                <div class="text-xl">INVOICE</div>
                <div>No: <strong>{{ $transaction->invoice_number }}</strong></div>
                <div>Tanggal: {{ $transaction->created_at->format('d M Y') }}</div>
                <div>Status: <span style="color: green; font-weight: bold;">LUNAS</span></div>
            </td>
        </tr>
    </table>

    <table class="header-table mt-8 mb-4">
        <tr>
            <td width="50%">
                <strong>Ditagihkan Kepada:</strong><br>
                {{ $transaction->customer->name }}<br>
                {{ $transaction->customer->email }}
            </td>
            <td width="50%" class="text-right">
                <strong>Metode Pembayaran:</strong><br>
                {{ strtoupper($transaction->payment_gateway ?? 'MIDTRANS') }}<br>
                {{ $transaction->payment_method ?? 'Payment Gateway' }}
            </td>
        </tr>
    </table>

    <table class="mt-8">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Langganan: {{ $transaction->subscription->subscriptionPlan->name ?? 'Produk SaaS' }}<br>
                    <small class="text-gray">Periode: {{ optional($transaction->subscription)->starts_at?->format('d M Y') }} - {{ optional($transaction->subscription)->ends_at?->format('d M Y') }}</small>
                </td>
                <td class="text-right">Rp {{ number_format((float) $transaction->gross_amount, 0, ',', '.') }}</td>
            </tr>
            @if((float) $transaction->voucher_discount > 0)
            <tr>
                <td class="text-right"><strong>Diskon Voucher</strong></td>
                <td class="text-right" style="color: red;">- Rp {{ number_format((float) $transaction->voucher_discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td class="text-right font-bold">Total Pembayaran</td>
                <td class="text-right font-bold">Rp {{ number_format((float) $transaction->net_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="mt-8 text-gray" style="font-size: 12px; text-align: center;">
        Ini adalah tanda terima pembayaran yang sah. Terima kasih telah berlangganan di COOCA.ID.
    </div>
</body>
</html>
