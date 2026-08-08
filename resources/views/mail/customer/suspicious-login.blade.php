<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Login Mencurigakan</title>
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
            <h1>COOCA.ID</h1>
        </div>
        <div class="content">
            <div class="security-icon">🔒</div>
            <h2 style="text-align: center; color: #EF4444;">Peringatan Keamanan Akun</h2>

            <p>Halo {{ $customerName }},</p>
            <p>Sistem keamanan kami mendeteksi percobaan login yang mencurigakan atau kegagalan login beruntun pada akun Anda:</p>

            <div class="info-box">
                <strong>Waktu Kejadian:</strong> {{ $timestamp }}<br>
                <strong>Alamat IP:</strong> {{ $ipAddress }}<br>
                <strong>Perangkat / Browser:</strong> {{ $userAgent }}
            </div>

            <p>Jika ini <strong>bukan</strong> Anda, harap segera amankan akun Anda dengan mengganti password dan mengaktifkan fitur keamanan tambahan di portal customer.</p>

            <p style="text-align: center;">
                <a href="{{ $securityUrl }}" class="btn">Amankan Akun Saya</a>
            </p>

            <p>Abaikan email ini jika aktivitas tersebut memang Anda lakukan sendiri.</p>

            <div class="footer">
                <p>Tim Keamanan Informasi COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
