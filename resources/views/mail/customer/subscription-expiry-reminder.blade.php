<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription akan Berakhir</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #F59E0B; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .warning-icon { font-size: 48px; text-align: center; color: #F59E0B; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; }
        .days-badge { background: #F59E0B; color: white; padding: 5px 15px; border-radius: 20px; display: inline-block; font-weight: bold; }
        .btn { display: inline-block; background: #4F46E5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Peringatan</h1>
        </div>
        <div class="content">
            <div class="warning-icon">⏰</div>
            <h2 style="text-align: center;">Subscription akan Berakhir</h2>

            <p>Halo {{ $customerName }},</p>
            <p>Subscription Anda untuk <strong>{{ $productName }}</strong> akan segera berakhir.</p>

            <div style="text-align: center; margin: 20px 0;">
                <span class="days-badge">{{ $daysUntilExpiry }} Hari Lagi</span>
            </div>

            <div class="info-box">
                <div class="info-row">
                    <span class="label">Produk</span>
                    <span class="value">{{ $productName }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Tanggal Berakhir</span>
                    <span class="value">{{ $expiresAt }}</span>
                </div>
            </div>

            <p>Perpanjang subscription Anda sekarang untuk menghindari gangguan layanan.</p>

            <p style="text-align: center;">
                <a href="{{ $renewalUrl }}" class="btn">Perpanjang Sekarang</a>
            </p>

            <div class="footer">
                <p>Jika Anda memiliki pertanyaan, silakan hubungi tim support kami.</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
