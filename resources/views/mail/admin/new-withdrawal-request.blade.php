<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Withdrawal Baru</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .alert-icon { font-size: 48px; text-align: center; color: #F59E0B; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        .value { color: #333; }
        .btn { display: inline-block; background: #4F46E5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .btn-secondary { display: inline-block; background: #6B7280; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; margin-left: 10px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
        </div>
        <div class="content">
            <div class="alert-icon">🔔</div>
            <h2 style="text-align: center;">Request Withdrawal Baru</h2>
            
            <p>Halo Admin,</p>
            <p>Ada request withdrawal baru dari affiliator yang perlu diproses.</p>
            
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Nama Affiliator</span>
                    <span class="value">{{ $affiliatorName }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $affiliatorEmail }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Jumlah Withdrawal</span>
                    <span class="value">Rp {{ $amount }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Fee Admin</span>
                    <span class="value">Rp {{ $fee }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Total Ditransfer</span>
                    <span class="value" style="font-weight: bold; color: #4F46E5;">Rp {{ $netAmount }}</span>
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
                <div class="info-row">
                    <span class="label">Diajukan Pada</span>
                    <span class="value">{{ $requestedAt }}</span>
                </div>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ $approvalUrl }}" class="btn">Proses Withdrawal</a>
                <a href="{{ route('admin.settlements.index') }}" class="btn-secondary">Lihat Semua Withdrawal</a>
            </p>
            
            <div class="footer">
                <p>COOCA.ID Admin Dashboard</p>
                <p>&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
