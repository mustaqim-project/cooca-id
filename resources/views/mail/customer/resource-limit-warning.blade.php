<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Batas Limit Resource</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #F59E0B; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .warning-icon { font-size: 48px; text-align: center; color: #F59E0B; margin-bottom: 10px; }
        .limit-box { background: #FEF3C7; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #F59E0B; color: #92400E; }
        .progress-container { background: #E5E7EB; border-radius: 9999px; height: 16px; margin: 15px 0; overflow: hidden; }
        .progress-bar { background: #F59E0B; height: 100%; border-radius: 9999px; }
        .btn { display: inline-block; background: #D97706; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID</h1>
        </div>
        <div class="content">
            <div class="warning-icon">⚠️</div>
            <h2 style="text-align: center; color: #D97706;">Batas Kuota Penggunaan Hampir Penuh</h2>

            <p>Halo {{ $customerName }},</p>
            <p>Kami ingin menginformasikan bahwa pemakaian tipe sumber daya (resource) langganan ERP Anda telah mendekati batas kuota paket yang aktif:</p>

            <div class="limit-box">
                <strong>Tipe Sumber Daya:</strong> {{ strtoupper($resourceType) }}<br>
                <strong>Persentase Pemakaian:</strong> {{ $usagePercentage }}%
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ min(100, $usagePercentage) }}%;"></div>
                </div>
            </div>

            <p>Untuk menghindari kegagalan sistem, pemblokiran input data baru, atau keterbatasan akses user, kami menyarankan Anda untuk segera melakukan upgrade paket langganan Anda.</p>

            <p style="text-align: center;">
                <a href="{{ $upgradeUrl }}" class="btn">Upgrade Paket Sekarang</a>
            </p>

            <div class="footer">
                <p>Tim Pengelola Resource COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
