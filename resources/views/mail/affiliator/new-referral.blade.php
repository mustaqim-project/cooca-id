<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Baru Terdaftar</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10B981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .success-icon { font-size: 48px; text-align: center; color: #10B981; margin-bottom: 10px; }
        .info-box { background: #ECFDF5; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10B981; color: #065F46; }
        .btn { display: inline-block; background: #10B981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID AFFILIATE</h1>
        </div>
        <div class="content">
            <div class="success-icon">🎉</div>
            <h2 style="text-align: center; color: #10B981;">Selamat! Pendaftaran Referral Sukses</h2>

            <p>Halo {{ $affiliatorName }},</p>
            <p>Seseorang baru saja mendaftar akun COOCA.ID menggunakan tautan atau kode referral Anda:</p>

            <div class="info-box">
                <strong>Nama Pengguna:</strong> {{ $referredCustomerName }}<br>
                <strong>Status Akun:</strong> Terdaftar (Menunggu Transaksi Pertama)
            </div>

            <p>Anda akan memperoleh komisi afiliasi secara otomatis setiap kali pengguna ini melakukan pembayaran tagihan lisensi paket SaaS atau custom development di COOCA.ID.</p>

            <p>Bantu mereka memahami produk kami dan dorong untuk melakukan upgrade ke berbayar agar komisi Anda segera aktif!</p>

            <p style="text-align: center;">
                <a href="{{ $affiliateDashboardUrl }}" class="btn">Buka Dashboard Afiliasi</a>
            </p>

            <div class="footer">
                <p>Tim Kemitraan Afiliasi COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
