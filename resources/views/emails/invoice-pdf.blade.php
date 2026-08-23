<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 32px 36px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #ffffff;
        }

        .wrapper {
            width: 100%;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            width: 100%;
            padding-bottom: 20px;
            border-bottom: 2px solid #111827;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand {
            width: 60%;
            vertical-align: top;
        }

        .brand-name {
            margin: 0;
            font-size: 25px;
            font-weight: bold;
            letter-spacing: -0.5px;
            color: #111827;
        }

        .brand-tagline {
            margin-top: 4px;
            font-size: 9px;
            color: #6b7280;
        }

        .invoice-title {
            width: 40%;
            text-align: right;
            vertical-align: top;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 25px;
            font-weight: bold;
            color: #111827;
        }

        .invoice-title p {
            margin: 5px 0 0;
            font-size: 9px;
            color: #6b7280;
        }

        /* =========================
           META
        ========================= */

        .meta {
            width: 100%;
            margin-top: 22px;
            border-collapse: collapse;
        }

        .meta td {
            vertical-align: top;
        }

        .invoice-info {
            width: 50%;
        }

        .customer-info {
            width: 50%;
            text-align: right;
        }

        .section-label {
            margin: 0 0 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #6b7280;
        }

        .info-row {
            margin-bottom: 5px;
            line-height: 1.45;
        }

        .info-label {
            display: inline-block;
            width: 85px;
            color: #6b7280;
        }

        .info-value {
            font-weight: bold;
            color: #111827;
        }

        .customer-name {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }

        .customer-detail {
            color: #4b5563;
            line-height: 1.5;
        }

        /* =========================
           STATUS
        ========================= */

        .status-wrapper {
            margin-top: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 20px;
        }

        .status-issued {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }

        .status-paid {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .status-overdue {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .status-cancelled {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        /* =========================
           BILLING SECTION
        ========================= */

        .section {
            margin-top: 25px;
        }

        .section-title {
            margin: 0 0 10px;
            font-size: 11px;
            font-weight: bold;
            color: #111827;
        }

        /* =========================
           INVOICE TABLE
        ========================= */

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .invoice-table th {
            padding: 10px 9px;
            background: #111827;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-align: left;
            border: 1px solid #111827;
        }

        .invoice-table td {
            padding: 11px 9px;
            font-size: 10px;
            color: #374151;
            vertical-align: top;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-table .description {
            width: 45%;
        }

        .invoice-table .product {
            width: 30%;
        }

        .invoice-table .amount {
            width: 25%;
            text-align: right;
        }

        .product-name {
            font-weight: bold;
            color: #111827;
        }

        .plan-name {
            margin-top: 3px;
            font-size: 9px;
            color: #6b7280;
        }

        /* =========================
           TOTAL
        ========================= */

        .total-wrapper {
            width: 100%;
            margin-top: 12px;
        }

        .total-table {
            width: 48%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .total-table td {
            padding: 6px 0;
            font-size: 10px;
        }

        .total-label {
            text-align: right;
            padding-right: 15px !important;
            color: #6b7280;
        }

        .total-value {
            width: 125px;
            text-align: right;
            color: #374151;
        }

        .grand-total td {
            padding-top: 10px;
            padding-bottom: 10px;
            border-top: 2px solid #111827;
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }

        /* =========================
           PAYMENT INFO
        ========================= */

        .payment-box {
            margin-top: 25px;
            padding: 13px 15px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .payment-title {
            margin: 0 0 6px;
            font-size: 10px;
            font-weight: bold;
            color: #111827;
        }

        .payment-text {
            margin: 0;
            font-size: 9px;
            line-height: 1.6;
            color: #6b7280;
        }

        /* =========================
           NOTES
        ========================= */

        .notes {
            margin-top: 22px;
            font-size: 9px;
            color: #6b7280;
            line-height: 1.6;
        }

        .notes-title {
            margin: 0 0 5px;
            font-weight: bold;
            color: #374151;
        }

        .notes p {
            margin: 3px 0;
        }

        .notes a {
            color: #111827;
            text-decoration: none;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 8px;
        }

        .footer strong {
            color: #6b7280;
        }

        /* =========================
           LICENSE BOX (PAID ONLY)
        ========================= */

        .license-box {
            margin-top: 20px;
            padding: 12px 15px;
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 4px;
        }

        .license-box-title {
            margin: 0 0 8px;
            font-size: 10px;
            font-weight: bold;
            color: #15803d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .license-table {
            width: 100%;
            border-collapse: collapse;
        }

        .license-table td {
            padding: 4px 0;
            font-size: 9px;
            vertical-align: top;
        }

        .license-label {
            width: 130px;
            color: #4b5563;
            font-weight: normal;
        }

        .license-val {
            font-family: DejaVu Sans Mono, monospace;
            font-weight: bold;
            color: #111827;
        }

        /* =========================
           UTILITIES
        ========================= */

        .text-right {
            text-align: right;
        }

        .text-muted {
            color: #6b7280;
        }

        .bold {
            font-weight: bold;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="header">

            <table class="header-table">
                <tr>

                    <td class="brand">

                        <img src="{{ public_path('assets/image/1782511520_logo_light.png') }}" alt="COOCA.ID"
                            style="
                        width: 110px;
                        height: auto;
                        display: block;
                        margin-bottom: 7px;
                    ">
                        <div class="brand-tagline">
                            Dari UMKM Untuk UMKM
                        </div>

                    </td>

                    <td class="invoice-title">

                        <h1>INVOICE</h1>

                        <p>
                            Invoice resmi pembayaran layanan COOCA.ID
                        </p>

                    </td>

                </tr>
            </table>

        </div>


        {{-- =========================================================
             INVOICE & CUSTOMER INFORMATION
        ========================================================== --}}

        <table class="meta">

            <tr>

                {{-- INVOICE INFORMATION --}}
                <td class="invoice-info">

                    <div class="section-label">
                        Invoice Information
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            Invoice No.
                        </span>

                        <span class="info-value">
                            {{ $invoice->invoice_number }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            Issue Date
                        </span>

                        <span class="info-value">
                            {{ $invoice->issued_at?->format('d M Y') ?? $invoice->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            Due Date
                        </span>

                        <span class="info-value">
                            {{ $invoice->due_at?->format('d M Y') ?? '—' }}
                        </span>
                    </div>

                    @if ($invoice->paid_at)
                        <div class="info-row">
                            <span class="info-label">
                                Paid Date
                            </span>

                            <span class="info-value">
                                {{ $invoice->paid_at->format('d M Y') }}
                            </span>
                        </div>
                    @endif


                    {{-- STATUS --}}
                    @php
                        $statusClass = match ($invoice->status) {
                            'paid' => 'status-paid',
                            'overdue' => 'status-overdue',
                            'cancelled' => 'status-cancelled',
                            default => 'status-issued',
                        };
                    @endphp

                    <div class="status-wrapper">

                        <span class="status-badge {{ $statusClass }}">
                            {{ ucfirst($invoice->status) }}
                        </span>

                    </div>

                </td>


                {{-- CUSTOMER INFORMATION --}}
                <td class="customer-info">

                    <div class="section-label">
                        Bill To
                    </div>

                    <div class="customer-name">
                        {{ $customer->name ?? ($invoice->customer?->name ?? 'Customer') }}
                    </div>

                    @if (!empty($customer->business_name ?? $invoice->customer?->business_name))
                        <div class="customer-detail">
                            {{ $customer->business_name ?? $invoice->customer?->business_name }}
                        </div>
                    @endif

                    @if (!empty($customer->email ?? $invoice->customer?->email))
                        <div class="customer-detail">
                            {{ $customer->email ?? $invoice->customer?->email }}
                        </div>
                    @endif

                    @if (!empty($customer->phone ?? $invoice->customer?->phone))
                        <div class="customer-detail">
                            {{ $customer->phone ?? $invoice->customer?->phone }}
                        </div>
                    @endif

                </td>

            </tr>

        </table>


        {{-- =========================================================
             INVOICE DETAIL
        ========================================================== --}}

        @php
            $subscription = $invoice->transaction?->subscription;
            $license = $subscription?->license ?? \App\Models\License::where('subscription_id', $subscription?->id)->first();
            $plan = $subscription?->subscriptionPlan;
            $product = $plan?->product ?? $subscription?->product ?? $invoice->transaction?->project;

            $durationText = '1 Bulan';
            if ($plan && $plan->duration_months) {
                if ($plan->duration_months % 12 === 0) {
                    $years = (int) ($plan->duration_months / 12);
                    $durationText = $years . ' Tahun (' . $plan->duration_months . ' Bulan)';
                } else {
                    $durationText = $plan->duration_months . ' Bulan';
                }
            } elseif ($subscription && $subscription->started_at && $subscription->expires_at) {
                $durationText = $subscription->started_at->diffForHumans($subscription->expires_at, true);
            }
        @endphp

        <div class="section">

            <div class="section-title">
                Detail Tagihan & Layanan
            </div>

            <table class="invoice-table">

                <thead>

                    <tr>

                        <th class="description">
                            Deskripsi Item & Durasi
                        </th>

                        <th class="product">
                            Produk / Paket
                        </th>

                        <th class="amount">
                            Jumlah (IDR)
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td class="description">

                            <div style="font-weight: bold; color: #111827;">
                                {{ $invoice->transaction?->description ?? 'Pembayaran Layanan SaaS COOCA.ID' }}
                            </div>

                            <div style="margin-top: 4px; font-size: 9px; color: #4b5563;">
                                <strong>Durasi:</strong> {{ $durationText }}
                                @if($subscription && $subscription->started_at && $subscription->expires_at)
                                    <br><strong>Masa Aktif:</strong> {{ $subscription->started_at->format('d M Y') }} s/d {{ $subscription->expires_at->format('d M Y') }}
                                @endif
                            </div>

                        </td>


                        <td class="product">

                            <div class="product-name">

                                {{ $product?->name ?? 'COOCA SaaS' }}

                            </div>


                            @if ($plan?->name)
                                <div class="plan-name">

                                    Paket: {{ $plan->name }}

                                </div>
                            @endif

                        </td>


                        <td class="amount nowrap">

                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- =========================================================
             TOTAL
        ========================================================== --}}

        <div class="total-wrapper">

            <table class="total-table">

                <tr>

                    <td class="total-label">
                        Subtotal
                    </td>

                    <td class="total-value">
                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                    </td>

                </tr>

                <tr>

                    <td class="total-label">
                        Diskon
                    </td>

                    <td class="total-value">
                        Rp 0
                    </td>

                </tr>

                <tr class="grand-total">

                    <td class="total-label">
                        TOTAL
                    </td>

                    <td class="total-value">
                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                    </td>

                </tr>

            </table>

        </div>

        {{-- =========================================================
             LICENSE CREDENTIALS (PAID INVOICE ONLY)
        ========================================================== --}}
        @if ($invoice->isPaid() && $license)
        <div class="license-box">
            <div class="license-box-title">
                Kredensial Lisensi Resmi (License Credentials)
            </div>
            <table class="license-table">
                <tr>
                    <td class="license-label">License Code:</td>
                    <td class="license-val">{{ $license->license_code }}</td>
                </tr>
                <tr>
                    <td class="license-label">License Key (Token):</td>
                    <td class="license-val">{{ $license->token_code }}</td>
                </tr>
                <tr>
                    <td class="license-label">Registered Email:</td>
                    <td class="license-val">{{ $customer->email ?? ($invoice->customer?->email ?? '-') }}</td>
                </tr>
                @if($license->domain)
                <tr>
                    <td class="license-label">Assigned Domain:</td>
                    <td class="license-val">{{ $license->domain }}</td>
                </tr>
                @endif
                @if($license->expires_at)
                <tr>
                    <td class="license-label">Valid Until:</td>
                    <td class="license-val">{{ $license->expires_at->format('d M Y') }}</td>
                </tr>
                @endif
            </table>
        </div>
        @endif


        {{-- =========================================================
             PAYMENT INFORMATION
        ========================================================== --}}

        <div class="payment-box">

            <div class="payment-title">
                Informasi Pembayaran
            </div>

            <p class="payment-text">
                Silakan melakukan pembayaran sesuai dengan nominal yang
                tercantum pada invoice ini sebelum tanggal jatuh tempo.
                Invoice ini diterbitkan secara otomatis oleh sistem COOCA.ID.
            </p>

        </div>


        {{-- =========================================================
             NOTES
        ========================================================== --}}

        <div class="notes">

            <div class="notes-title">
                Catatan
            </div>

            <p>
                Terima kasih telah menggunakan layanan COOCA.ID.
            </p>

            <p>
                Mohon selesaikan pembayaran sebelum tanggal jatuh tempo
                untuk menjaga layanan tetap aktif.
            </p>

            <p>
                Jika Anda memiliki pertanyaan mengenai invoice ini,
                silakan hubungi tim support melalui
                <a href="mailto:support@cooca.id">
                    support@cooca.id
                </a>.
            </p>

        </div>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="footer">

            Invoice ini dibuat secara otomatis oleh
            <strong>COOCA.ID</strong>.

            <br>

            Tidak diperlukan tanda tangan atau stempel.

        </div>

    </div>

</body>

</html>
