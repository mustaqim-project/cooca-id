<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Termin Baru Proyek</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .billing-box { background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4F46E5; }
        .billing-row { margin: 15px 0; }
        .billing-label { font-weight: bold; color: #666; display: block; margin-bottom: 5px; }
        .billing-value { background: #f0f0f0; padding: 10px; border-radius: 4px; display: block; font-weight: bold; }
        .btn { display: inline-block; background: #4F46E5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID</h1>
        </div>
        <div class="content">
            <h2 style="text-align: center; color: #4F46E5;">Tagihan Termin Proyek Baru Diterbitkan</h2>

            <p>Halo {{ $customerName }},</p>
            <p>Admin kami baru saja menerbitkan link tagihan pembayaran baru untuk proyek Anda: <strong>{{ $projectName }}</strong>.</p>

            <div class="billing-box">
                <div class="billing-row">
                    <span class="billing-label">Nomor Invoice:</span>
                    <span class="billing-value" style="font-family: monospace;">{{ $invoiceNumber }}</span>
                </div>
                <div class="billing-row">
                    <span class="billing-label">Deskripsi Termin / Milestone:</span>
                    <span class="billing-value">{{ $description }}</span>
                </div>
                <div class="billing-row">
                    <span class="billing-label">Jumlah Tagihan:</span>
                    <span class="billing-value" style="color: #4F46E5; font-size: 18px;">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <p style="text-align: center;">
                <a href="{{ $paymentUrl }}" class="btn">Bayar Sekarang via Midtrans</a>
            </p>

            <p>Harap segera selesaikan pembayaran termin agar proses implementasi proyek Anda dapat berjalan sesuai timeline yang direncanakan.</p>

            <div class="footer">
                <p>Terima kasih telah mempercayai COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
