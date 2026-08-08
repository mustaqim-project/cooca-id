<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #DC2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .failed-icon { font-size: 48px; text-align: center; color: #DC2626; margin-bottom: 10px; }
        .error-box { background: #FFF5F5; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #DC2626; color: #9B1C1C; }
        .btn { display: inline-block; background: #DC2626; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COOCA.ID</h1>
        </div>
        <div class="content">
            <div class="failed-icon">⚠️</div>
            <h2 style="text-align: center; color: #DC2626;">Transaksi Pembayaran Anda Gagal</h2>

            <p>Halo {{ $customerName }},</p>
            <p>Kami mendeteksi bahwa percobaan pembayaran terakhir Anda untuk invoice berikut telah gagal diproses oleh payment gateway:</p>

            <div class="error-box">
                <strong>Nomor Invoice:</strong> {{ $invoiceNumber }}<br>
                <strong>Jumlah:</strong> Rp {{ number_format($amount, 0, ',', '.') }}<br>
                <strong>Alasan Kegagalan:</strong> {{ $errorMessage ?? 'Ditolak oleh bank penerbit / limit saldo tidak mencukupi.' }}
            </div>

            <p>Anda dapat mencoba melakukan pembayaran kembali menggunakan metode pembayaran lain dengan mengklik tombol di bawah:</p>

            <p style="text-align: center;">
                <a href="{{ $retryUrl }}" class="btn">Coba Pembayaran Lagi</a>
            </p>

            <p>Jika saldo rekening Anda terpotong namun status belum terupdate, silakan hubungi tim dukungan kami melalui modul Tiket Bantuan.</p>

            <div class="footer">
                <p>Tim Dukungan Keuangan COOCA.ID</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
