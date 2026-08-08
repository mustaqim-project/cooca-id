<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Check Failure Alert</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #DC2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .alert-icon { font-size: 48px; text-align: center; color: #DC2626; margin-bottom: 10px; }
        .details-box { background: #FFF5F5; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #DC2626; color: #9B1C1C; }
        .btn { display: inline-block; background: #DC2626; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID ADMIN SYSTEM</h1>
        </div>
        <div class="content">
            <div class="alert-icon">🚨</div>
            <h2 style="text-align: center; color: #DC2626;">ALERT: Tenant Offline / Gagal Respon</h2>

            <p>Halo Administrator,</p>
            <p>Sistem monitoring mendeteksi kegagalan koneksi (*health check failure*) pada salah satu instansi ERP penyewa berikut:</p>

            <div class="details-box">
                <strong>Nama Tenant:</strong> {{ $tenantName }}<br>
                <strong>Subdomain:</strong> {{ $subdomain }}<br>
                <strong>Waktu Gagal:</strong> {{ $timestamp }}<br>
                <strong>Detail Kesalahan:</strong> {{ $errorDetails }}
            </div>

            <p>Silakan segera lakukan investigasi pada server provisioning target untuk memeriksa utilisasi database, resource VM, atau potensi overload network.</p>

            <p style="text-align: center;">
                <a href="{{ $adminUrl }}" class="btn">Buka Monitoring System</a>
            </p>

            <div class="footer">
                <p>Notifikasi Otomatis Monitoring Engine COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
