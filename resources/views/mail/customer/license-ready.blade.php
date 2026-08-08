<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Siap Digunakan</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .success-icon { font-size: 48px; text-align: center; color: #10B981; }
        .credential-box { background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4F46E5; }
        .credential-row { margin: 15px 0; }
        .credential-label { font-weight: bold; color: #666; display: block; margin-bottom: 5px; }
        .credential-value { font-family: monospace; background: #f0f0f0; padding: 10px; border-radius: 4px; display: block; word-break: break-all; }
        .btn { display: inline-block; background: #4F46E5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID</h1>
        </div>
        <div class="content">
            <div class="success-icon">🎉</div>
            <h2 style="text-align: center;">License Siap Digunakan!</h2>

            <p>Halo {{ $customerName }},</p>
            <p>License untuk produk <strong>{{ $productName }}</strong> telah siap digunakan.</p>

            <div class="credential-box">
                <div class="credential-row">
                    <span class="credential-label">License Code:</span>
                    <span class="credential-value">{{ $licenseCode }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Token Code:</span>
                    <span class="credential-value">{{ $tokenCode }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Domain Terdaftar:</span>
                    <span class="credential-value">{{ $domain }}</span>
                </div>
            </div>

            <p style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="btn">Buka Dashboard</a>
            </p>

            <p><strong>Penting:</strong> Simpan credential ini dengan baik. Anda akan membutuhkannya untuk mengaktifkan sistem ERP Anda.</p>

            <div class="footer">
                <p>Terima kasih telah menggunakan COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
