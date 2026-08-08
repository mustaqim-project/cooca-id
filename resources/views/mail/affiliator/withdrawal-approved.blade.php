<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Disetujui</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10B981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .success-icon { font-size: 48px; text-align: center; color: #10B981; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; }
        .net-amount { font-size: 24px; font-weight: bold; color: #10B981; }
        .timeline { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .timeline-item { display: flex; align-items: center; padding: 10px 0; }
        .timeline-icon { width: 30px; height: 30px; background: #10B981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID Affiliate</h1>
        </div>
        <div class="content">
            <div class="success-icon">✓</div>
            <h2 style="text-align: center;">Withdrawal Disetujui!</h2>

            <p>Halo {{ $affiliatorName }},</p>
            <p>Request withdrawal Anda telah disetujui dan sedang diproses untuk transfer.</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="label">Jumlah Withdrawal</span>
                    <span class="value">Rp {{ $amount }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Fee Admin</span>
                    <span class="value">Rp {{ $fee }}</span>
                </div>
                <div class="info-row" style="background: #f0fdf4; padding: 15px; border-radius: 8px; margin-top: 10px;">
                    <span class="label">Total Diterima</span>
                    <span class="net-amount">Rp {{ $netAmount }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Metode</span>
                    <span class="value">{{ $withdrawalMethod }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Nomor Rekening</span>
                    <span class="value">{{ $accountNumber }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Atas Nama</span>
                    <span class="value">{{ $accountName }}</span>
                </div>
            </div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">✓</div>
                    <div>
                        <strong>Disetujui</strong><br>
                        <small>Withdrawal Anda telah disetujui oleh admin</small>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon" style="background: #F59E0B;">⏳</div>
                    <div>
                        <strong>Dalam Proses Transfer</strong><br>
                        <small>Estimasi: 1-3 hari kerja</small>
                    </div>
                </div>
            </div>

            <div class="footer">
                <p>Terima kasih atas partisipasi Anda dalam program affiliate COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
