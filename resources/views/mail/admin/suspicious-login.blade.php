<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Suspicious Login Activity</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1E293B; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .security-icon { font-size: 48px; text-align: center; color: #EF4444; margin-bottom: 10px; }
        .info-box { background: #F1F5F9; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #1E293B; }
        .btn { display: inline-block; background: #EF4444; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID SECURITY SERVICE</h1>
        </div>
        <div class="content">
            <div class="security-icon">🛡️</div>
            <h2 style="text-align: center; color: #EF4444;">Peringatan Keamanan Akses Admin</h2>

            <p>Halo {{ $adminName }},</p>
            <p>Sistem keamanan mendeteksi aktivitas login admin atau kegagalan beruntun yang mencurigakan di luar kebiasaan:</p>

            <div class="info-box">
                <strong>Admin User:</strong> {{ $adminName }}<br>
                <strong>Waktu:</strong> {{ $timestamp }}<br>
                <strong>IP Address:</strong> {{ $ipAddress }}<br>
                <strong>Device/User Agent:</strong> {{ $userAgent }}
            </div>

            <p>Jika aktivitas ini bukan berasal dari Anda atau IP resmi tim IT, segera amankan kredensial admin dan kunci akses database backend.</p>

            <p style="text-align: center;">
                <a href="{{ $securityUrl }}" class="btn">Ganti Password Admin</a>
            </p>

            <div class="footer">
                <p>Notifikasi Keamanan Otomatis COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
