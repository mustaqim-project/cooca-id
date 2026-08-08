<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email Anda - COOCA.ID</title>
    <style>
        /* Reset & Base Styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; }

        /* Container */
        .wrapper { width: 100%; table-layout: fixed; background-color: #f1f5f9; padding: 40px 0; }
        .main-card { background-color: #ffffff; margin: 0 auto; max-width: 600px; width: 100%; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04); }

        /* Header Gradient */
        .header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 40px 30px; text-align: center; color: #ffffff; position: relative; }
        .logo-badge { display: inline-block; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); padding: 8px 20px; border-radius: 50px; font-size: 14px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #ffffff; margin-bottom: 16px; border: 1px solid rgba(255, 255, 255, 0.25); }
        .header-title { margin: 0; font-size: 26px; font-weight: 800; line-height: 1.3; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-sub { margin: 8px 0 0 0; font-size: 15px; color: #e0e7ff; font-weight: 400; }

        /* Body Content */
        .content { padding: 40px 35px; background-color: #ffffff; }
        .greeting { font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px; }
        .text { font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 24px; }

        /* CTA Button */
        .cta-container { text-align: center; margin: 36px 0; }
        .btn-primary { display: inline-block; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: #ffffff !important; font-size: 16px; font-weight: 700; text-decoration: none; padding: 16px 42px; border-radius: 12px; box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4); transition: all 0.3s ease; letter-spacing: 0.3px; }

        /* Features Box */
        .features-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; margin: 28px 0; }
        .features-title { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; margin-top: 0; margin-bottom: 16px; }
        .feature-item { display: table; width: 100%; margin-bottom: 12px; font-size: 14px; color: #334155; }
        .feature-item:last-child { margin-bottom: 0; }
        .feature-icon { display: table-cell; width: 24px; vertical-align: top; font-weight: bold; color: #4f46e5; }
        .feature-text { display: table-cell; vertical-align: middle; }

        /* Notice / Alert Box */
        .alert-box { background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 8px; font-size: 13px; color: #1e40af; line-height: 1.5; margin-top: 24px; }

        /* Link Fallback */
        .fallback-link { font-size: 12px; color: #94a3b8; word-break: break-all; margin-top: 28px; line-height: 1.5; padding-top: 20px; border-top: 1px solid #f1f5f9; }
        .fallback-link a { color: #4f46e5; text-decoration: underline; }

        /* Footer */
        .footer { background-color: #f8fafc; padding: 28px 30px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer-text { font-size: 13px; color: #64748b; margin: 0 0 8px 0; line-height: 1.5; }
        .footer-links { font-size: 12px; color: #94a3b8; }
        .footer-links a { color: #64748b; text-decoration: none; margin: 0 8px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-card">
            <!-- Header Section -->
            <div class="header">
                <div class="logo-badge">COOCA.ID</div>
                <h1 class="header-title">Verifikasi Alamat Email Anda</h1>
                <p class="header-sub">Satu langkah lagi untuk mengaktifkan akun Cooca.id Anda</p>
            </div>

            <!-- Body Section -->
            <div class="content">
                <p class="greeting">Halo, {{ $customerName }} 👋</p>
                <p class="text">
                    Terima kasih telah mendaftar di <strong>COOCA.ID</strong> untuk bisnis <strong>{{ $businessName }}</strong>.
                    Untuk memastikan keamanan akun Anda dan mengakses seluruh layanan ERP kami, silakan konfirmasi alamat email Anda dengan menekan tombol di bawah ini:
                </p>

                <!-- Primary CTA Button -->
                <div class="cta-container">
                    <a href="{{ $verificationUrl }}" target="_blank" class="btn-primary">Verifikasi Email Saya</a>
                </div>

                <!-- Features Card -->
                <div class="features-card">
                    <div class="features-title">Layanan Yang Dapat Anda Akses:</div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Sistem Manajemen ERP Lengkap & Modul Bisnis</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Aktivasi Lisensi & Manajemen Domain Otomatis</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Dukungan Teknis Prioritas 24/7</div>
                    </div>
                </div>

                <!-- Security Alert Box -->
                <div class="alert-box">
                    <strong>📌 Catatan Keamanan:</strong> Link verifikasi ini akan kadaluwarsa dalam <strong>60 menit</strong>. Jika Anda tidak merasa mendaftar di Cooca.id, Anda dapat mengabaikan email ini.
                </div>

                <!-- Fallback URL -->
                <div class="fallback-link">
                    Jika tombol di atas tidak dapat diklik, salin dan tempel URL berikut ke browser Anda:<br>
                    <a href="{{ $verificationUrl }}" target="_blank">{{ $verificationUrl }}</a>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="footer">
                <p class="footer-text">
                    <strong>COOCA.ID</strong> — Platform Enterprise Resource Planning Terpercaya.<br>
                    Mari tumbuh dan berkembang bersama teknologi modern.
                </p>
                <div class="footer-links">
                    <a href="https://cooca.id">Website</a> •
                    <a href="https://cooca.id">Bantuan Support</a> •
                    <a href="https://cooca.id">Kebijakan Privasi</a>
                </div>
                <p style="font-size: 11px; color: #cbd5e1; margin-top: 12px;">&copy; {{ date('Y') }} COOCA.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
