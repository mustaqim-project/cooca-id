<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Resource Limit Warning</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #F59E0B; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .warning-icon { font-size: 48px; text-align: center; color: #F59E0B; margin-bottom: 10px; }
        .limit-box { background: #FEF3C7; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #F59E0B; color: #92400E; }
        .btn { display: inline-block; background: #D97706; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID ADMIN SYSTEM</h1>
        </div>
        <div class="content">
            <div class="warning-icon">⚠️</div>
            <h2 style="text-align: center; color: #D97706;">Tenant Mendekati Limit Sumber Daya</h2>

            <p>Halo Administrator,</p>
            <p>Sistem mencatat adanya penggunaan sumber daya (*resource utilization*) yang kritis oleh instansi penyewa berikut:</p>

            <div class="limit-box">
                <strong>Nama Tenant:</strong> {{ $tenantName }}<br>
                <strong>Subdomain:</strong> {{ $subdomain }}<br>
                <strong>Tipe Resource:</strong> {{ strtoupper($resourceType) }}<br>
                <strong>Persentase Pemakaian:</strong> {{ $usagePercentage }}%
            </div>

            <p>Tindakan yang direkomendasikan:</p>
            <ul>
                <li>Hubungi akun perwakilan atau Account Manager untuk menawarkan opsi upgrade paket langganan.</li>
                <li>Pantau kapasitas disk space / server limits di panel monitoring utama.</li>
            </ul>

            <p style="text-align: center;">
                <a href="{{ $adminUrl }}" class="btn">Periksa Detail Tenant</a>
            </p>

            <div class="footer">
                <p>Notifikasi Otomatis Resource Manager COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
