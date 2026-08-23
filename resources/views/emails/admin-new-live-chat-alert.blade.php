<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Live Chat Baru — {{ $siteName }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 24px;
            color: #1e293b;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #075E54 0%, #128C7E 100%);
            padding: 22px 28px;
            color: #ffffff;
            text-align: center;
        }
        .body {
            padding: 28px;
        }
        .message-box {
            background: #f8fafc;
            border-left: 4px solid #128C7E;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.5;
            color: #334155;
            font-style: italic;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .data-table td {
            padding: 7px 0;
            border-bottom: 1px dashed #f1f5f9;
        }
        .label {
            color: #64748b;
            width: 140px;
            font-weight: 600;
        }
        .value {
            font-weight: 700;
            color: #0f172a;
        }
        .btn-action {
            display: inline-block;
            background: #128C7E;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            margin-top: 10px;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 16px 28px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2 style="margin:0; font-size:18px;">💬 Request Live Chat Baru Masuk</h2>
            <p style="margin:4px 0 0; font-size:13px; opacity:0.9;">Ada pengunjung website yang memulai sesi live chat realtime.</p>
        </div>

        <div class="body">
            <div style="font-size:12px; font-weight:700; color:#075E54; text-transform:uppercase; margin-bottom:6px;">Pesan Pertanyaan Awal:</div>
            <div class="message-box">
                "{{ $initialMessage }}"
            </div>

            <div style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px; border-bottom:1px solid #e2e8f0; padding-bottom:4px;">
                👤 Kontak Pengunjung
            </div>
            <table class="data-table">
                <tr>
                    <td class="label">Nama Lengkap:</td>
                    <td class="value">{{ $chat->customer_name }}</td>
                </tr>
                <tr>
                    <td class="label">Nomor WhatsApp:</td>
                    <td class="value">
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $chat->customer_phone) }}" target="_blank" style="color:#16a34a; font-weight:700;">
                            +{{ $chat->customer_phone }}
                        </a>
                    </td>
                </tr>
                @if($chat->customer_email)
                <tr>
                    <td class="label">Email:</td>
                    <td class="value"><a href="mailto:{{ $chat->customer_email }}" style="color:#2563eb;">{{ $chat->customer_email }}</a></td>
                </tr>
                @endif
                <tr>
                    <td class="label">Waktu Masuk:</td>
                    <td class="value">{{ now()->translatedFormat('d M Y, H:i:s') }} WIB</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('admin.live-chats.index') }}" class="btn-action">Balas Realtime di Admin Panel &rarr;</a>
            </div>
        </div>

        <div class="footer">
            Email ini dikirim otomatis ke alamat tim administrator <strong>{{ $siteName }}</strong>:<br>
            <code>agungmustaqim15@gmail.com</code> & <code>cooca.idn@gmail.com</code>
        </div>
    </div>
</body>
</html>
