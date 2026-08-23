<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Live Chat — {{ $siteName }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 24px;
            color: #1e293b;
        }
        .email-card {
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
            padding: 24px 28px;
            color: #ffffff;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .body {
            padding: 24px 28px;
        }
        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }
        .meta-label {
            color: #64748b;
            font-weight: 600;
        }
        .meta-val {
            font-weight: 700;
            color: #1e293b;
        }
        .transcript-title {
            font-size: 14px;
            font-weight: 700;
            color: #075E54;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
        }
        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }
        .chat-bubble {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 8px;
        }
        .bubble-customer {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
        }
        .bubble-admin {
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
        }
        .bubble-system {
            background: #f1f5f9;
            border-left: 4px solid #94a3b8;
            font-style: italic;
            font-size: 12px;
            color: #64748b;
        }
        .chat-sender {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .chat-sender.customer { color: #0369a1; }
        .chat-sender.admin { color: #15803d; }
        .chat-sender.system { color: #475569; }
        .chat-time {
            font-size: 10px;
            color: #94a3b8;
            float: right;
            font-weight: normal;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 16px 28px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
        .btn-action {
            display: inline-block;
            background: #128C7E;
            color: #ffffff !important;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h1>{{ $siteName }} Live Support</h1>
            <p>Ringkasan & Transkrip Sesi Percakapan</p>
        </div>

        <div class="body">
            <p style="font-size: 14px; margin-top: 0;">
                Halo <strong>{{ $chat->customer_name }}</strong>,
            </p>
            <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                Terima kasih telah berkonsultasi melalui Live Chat Support <strong>{{ $siteName }}</strong>. Berikut adalah rekaman riwayat percakapan Anda:
            </p>

            <div class="meta-box">
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600; width: 140px;">Nama Customer:</td>
                        <td style="padding: 4px 0; font-weight: 700;">{{ $chat->customer_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Nomor WhatsApp:</td>
                        <td style="padding: 4px 0; font-weight: 700;">+{{ $chat->customer_phone }}</td>
                    </tr>
                    @if($chat->customer_email)
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Alamat Email:</td>
                        <td style="padding: 4px 0; font-weight: 700;">{{ $chat->customer_email }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Waktu Dimulai:</td>
                        <td style="padding: 4px 0; font-weight: 700;">{{ $chat->created_at->translatedFormat('d M Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Waktu Selesai:</td>
                        <td style="padding: 4px 0; font-weight: 700;">{{ $chat->ended_at ? $chat->ended_at->translatedFormat('d M Y, H:i') . ' WIB' : '—' }}</td>
                    </tr>
                </table>
            </div>

            <div class="transcript-title">💬 Riwayat Pesan (Transkrip)</div>

            <div class="chat-list">
                @forelse($messages as $msg)
                    @php
                        $isCust = $msg->sender_type === 'customer';
                        $isSys = $msg->sender_type === 'system';
                        $bubbleClass = $isSys ? 'bubble-system' : ($isCust ? 'bubble-customer' : 'bubble-admin');
                        $senderClass = $isSys ? 'system' : ($isCust ? 'customer' : 'admin');
                        $senderName = $isSys ? 'System' : ($isCust ? $chat->customer_name : 'Admin Cooca');
                    @endphp
                    <div class="chat-bubble {{ $bubbleClass }}">
                        <div class="chat-sender {{ $senderClass }}">
                            {{ $senderName }}
                            <span class="chat-time">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                        <div style="white-space: pre-wrap; word-break: break-word;">{{ $msg->message }}</div>
                    </div>
                @empty
                    <div style="font-size: 13px; color: #94a3b8; font-style: italic;">Tidak ada rekaman pesan.</div>
                @endforelse
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ url('/') }}" class="btn-action">Kunjungi Website {{ $siteName }}</a>
            </div>
        </div>

        <div class="footer">
            Email ini dikirimkan secara otomatis oleh sistem Live Support <strong>{{ $siteName }}</strong>.<br>
            Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim kami via WhatsApp atau balas email ini.
        </div>
    </div>
</body>
</html>
