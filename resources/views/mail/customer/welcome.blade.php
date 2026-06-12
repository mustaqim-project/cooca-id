<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Bergabung di COOCA.ID</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .welcome-icon { font-size: 64px; text-align: center; }
        .btn { display: inline-block; background: #4F46E5; color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; margin: 20px 10px; font-weight: bold; }
        .features { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .feature-item { padding: 10px 0; border-bottom: 1px solid #eee; }
        .feature-item:last-child { border-bottom: none; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat Datang!</h1>
        </div>
        <div class="content">
            <div class="welcome-icon">👋</div>
            <h2 style="text-align: center;">Selamat Bergabung di COOCA.ID!</h2>
            
            <p>Halo {{ $customerName }},</p>
            <p>Terima kasih telah bergabung dengan <strong>COOCA.ID</strong>. Kami senang menyambut {{ $businessName }} sebagai bagian dari komunitas kami.</p>
            
            <p>Dengan COOCA.ID, Anda dapat mengakses berbagai sistem ERP profesional untuk mengembangkan bisnis Anda:</p>
            
            <div class="features">
                <div class="feature-item">✓ Sistem ERP Restoran</div>
                <div class="feature-item">✓ Sistem Manajemen Klinik</div>
                <div class="feature-item">✓ Sistem Bengkel & Otomotif</div>
                <div class="feature-item">✓ Sistem Legal & Notaris</div>
                <div class="feature-item">✓ Sistem Booking & Reservasi</div>
                <div class="feature-item">✓ Dan masih banyak lagi...</div>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" class="btn">Login Sekarang</a>
                <a href="{{ $dashboardUrl }}" class="btn" style="background: #10B981;">Buka Dashboard</a>
            </p>
            
            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, tim support kami siap membantu 24/7.</p>
            
            <div class="footer">
                <p>Mari tumbuh bersama COOCA.ID!</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
