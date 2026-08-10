<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            padding: 24px;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header small {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .section {
            margin-top: 24px;
        }

        .grid {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .box {
            width: 100%;
            max-width: 48%;
            background: #f9f9f9;
            padding: 14px;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .box h3 {
            margin: 0 0 10px;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .status-issued {
            background: #fff4e5;
            color: #b35d00;
            border: 1px solid #ffd8a8;
        }

        .status-paid {
            background: #e6ffed;
            color: #066f27;
            border: 1px solid #a7f3d0;
        }

        .status-overdue {
            background: #ffe7e7;
            color: #a11212;
            border: 1px solid #fca5a5;
        }

        .status-cancelled {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background: #f3f4f6;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-top: 16px;
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 8px;
        }

        .totals .label {
            text-align: right;
            font-weight: bold;
            width: 80%;
        }

        .totals .value {
            text-align: right;
            width: 20%;
        }

        .notes {
            margin-top: 24px;
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <img src="{{ public_path('assets/image/1782509862_logo.png') }}" alt="COOCA.ID"
                style="max-width: 200px; height: auto; object-fit: contain; margin-bottom: 12px;" />
            <h2>COOCA.ID</h2>
            {{-- <small>PT COOCA TECHNOLOGIES INDONESIA | cooca.id</small> --}}
        </div>

        <div class="section grid">
            <div class="box">
                <h3>Invoice</h3>
                <div><strong>No. Invoice:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Issue Date:</strong>
                    {{ $invoice->issued_at?->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</div>
                <div><strong>Due Date:</strong> {{ $invoice->due_at?->format('d M Y') ?? '—' }}</div>
                @if ($invoice->paid_at)
                    <div><strong>Paid Date:</strong> {{ $invoice->paid_at->format('d M Y') }}</div>
                @endif
                <div style="margin-top: 10px;">
                    @php
                        $statusClass = match ($invoice->status) {
                            'paid' => 'status-paid',
                            'overdue' => 'status-overdue',
                            'cancelled' => 'status-cancelled',
                            default => 'status-issued',
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            </div>

            <div class="box">
                <h3>Bill To</h3>
                <div><strong>Name:</strong> {{ $customer->name ?? $invoice->customer?->name }}</div>
                <div><strong>Email:</strong> {{ $customer->email ?? $invoice->customer?->email }}</div>
                @if (!empty($customer->phone ?? $invoice->customer?->phone))
                    <div><strong>Phone:</strong> {{ $customer->phone ?? $invoice->customer?->phone }}</div>
                @endif
                @if (!empty($customer->business_name ?? $invoice->customer?->business_name))
                    <div><strong>Company:</strong> {{ $customer->business_name ?? $invoice->customer?->business_name }}
                    </div>
                @endif
            </div>
        </div>

        <div class="section">
            <h3>Detail Tagihan</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Product / Plan</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->transaction?->description ?? 'Pembayaran langganan SaaS Cooca' }}</td>
                        <td>
                            {{ $invoice->transaction?->subscription?->product?->name ?? 'COOCA SaaS Subscription' }}
                            @if ($invoice->transaction?->subscription?->subscriptionPlan?->name)
                                <div style="font-size:11px;color:#555;">
                                    {{ $invoice->transaction->subscription->subscriptionPlan->name }}</div>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="totals">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">Rp 0</td>
                </tr>
                <tr>
                    <td class="label">Total</td>
                    <td class="value"><strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="section notes">
            <p><strong>Catatan:</strong></p>
            <p>Terima kasih telah menggunakan layanan COOCA.ID. Mohon selesaikan pembayaran sebelum tanggal jatuh tempo.
            </p>
            <p>Jika Anda memiliki pertanyaan tentang invoice ini, silakan hubungi tim dukungan kami di <a
                    href="mailto:support@cooca.id">support@cooca.id</a>.</p>
        </div>

        <div class="footer">
            <small>Invoice generated automatically oleh sistem COOCA.ID.</small>
        </div>
    </div>
</body>

</html>
