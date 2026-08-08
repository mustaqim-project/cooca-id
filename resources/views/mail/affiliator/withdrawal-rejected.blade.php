<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Ditolak</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #EF4444; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .error-icon { font-size: 48px; text-align: center; color: #EF4444; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; }
        .reason-box { background: #FEF2F2; border: 1px solid #FCA5A5; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .reason-label { font-weight: bold; color: #DC2626; margin-bottom: 10px; display: block; }
        .btn { display: inline-block; background: #4F46E5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID Affiliate</h1>
        </div>
        <div class="content">
            <div class="error-icon">✕</div>
            <h2 style="text-align: center;">Withdrawal Ditolak</h2>

            <p>Halo {{ $affiliatorName }},</p>
            <p>Maaf, request withdrawal Anda tidak dapat diproses.</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="label">Jumlah Withdrawal</span>
                    <span class="value">Rp {{ $amount }}</span>
                </div>
            </div>

            <div class="reason-box">
                <span class="reason-label">Alasan Penolakan:</span>
                <p style="margin: 0;">{{ $rejectionReason }}</p>
            </div>

            <p>Jika Anda memiliki pertanyaan atau ingin mengajukan withdrawal kembali, silakan hubungi tim support kami atau ajukan withdrawal baru setelah memperbaiki masalah yang disebutkan di atas.</p>

            <p style="text-align: center;">
                <a href="{{ route('affiliator.withdrawals.index') }}" class="btn">Ajukan Withdrawal Baru</a>
            </p>

            <div class="footer">
                <p>Terima kasih atas pengertian Anda</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
