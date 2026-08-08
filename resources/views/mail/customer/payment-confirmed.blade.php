<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .success-icon { font-size: 48px; text-align: center; color: #10B981; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID</h1>
        </div>
        <div class="content">
            <div class="success-icon">✓</div>
            <h2 style="text-align: center;">Pembayaran Berhasil!</h2>

            <p>Halo {{ $customerName }},</p>
            <p>Terima kasih atas pembayaran Anda. Pembayaran untuk invoice berikut telah berhasil diproses:</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="label">Nomor Invoice</span>
                    <span class="value">{{ $invoiceNumber }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Produk</span>
                    <span class="value">{{ $productName }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Jumlah Dibayar</span>
                    <span class="value">Rp {{ $amount }}</span>
                </div>
            </div>

            <p>Anda dapat mengakses layanan Anda melalui dashboard customer.</p>

            <div class="footer">
                <p>Terima kasih telah menggunakan COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
