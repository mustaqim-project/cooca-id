<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komisi Diterima</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10B981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .money-icon { font-size: 48px; text-align: center; color: #10B981; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; font-weight: bold; }
        .balance-box { background: #10B981; color: white; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .balance-label { font-size: 14px; opacity: 0.9; }
        .balance-amount { font-size: 32px; font-weight: bold; margin-top: 10px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID Affiliate</h1>
        </div>
        <div class="content">
            <div class="money-icon">💰</div>
            <h2 style="text-align: center;">Komisi Diterima!</h2>

            <p>Halo {{ $affiliatorName }},</p>
            <p>Anda telah menerima komisi baru dari program affiliate COOCA.ID.</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="label">Jumlah Komisi</span>
                    <span class="value">Rp {{ $commissionAmount }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Level</span>
                    <span class="value">Level {{ $level }}</span>
                </div>
            </div>

            <div class="balance-box">
                <div class="balance-label">Saldo Anda Sekarang</div>
                <div class="balance-amount">Rp {{ $newBalance }}</div>
            </div>

            <p>Terus kembangkan jaringan Anda untuk mendapatkan lebih banyak komisi!</p>

            <div class="footer">
                <p>Terima kasih atas partisipasi Anda dalam program affiliate COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
