<?php

declare(strict_types=1);

namespace App\Services\LiveChat;

use App\Models\LiveChat;
use App\Models\LiveChatMessage;
use App\Models\WhatsAppDevice;
use App\Services\WhatsAppGatewayService;
use Illuminate\Support\Facades\Log;

final class LiveChatService
{
    protected WhatsAppGatewayService $gatewayService;

    public function __construct(WhatsAppGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Automatically end live chat sessions that have been inactive for >= 30 minutes.
     *
     * @return int Number of sessions ended
     */
    public function autoEndInactiveChats(): int
    {
        $activeChats = LiveChat::where('status', 'active')->get();
        $endedCount = 0;

        foreach ($activeChats as $chat) {
            // Determine last activity time based on the latest message or updated_at
            $lastMessage = LiveChatMessage::where('live_chat_id', $chat->id)
                ->orderBy('id', 'desc')
                ->first();

            $lastActivityTime = $lastMessage ? $lastMessage->created_at : $chat->updated_at;

            if ($lastActivityTime && $lastActivityTime->lte(now()->subMinutes(30))) {
                $this->endChatSession(
                    $chat,
                    'system',
                    'Percakapan otomatis diakhiri karena tidak ada aktivitas selama 30 menit.'
                );
                $endedCount++;
            }
        }

        return $endedCount;
    }

    /**
     * End a live chat session and send WhatsApp and Email transcript.
     */
    public function endChatSession(LiveChat $chat, string $endedBy = 'admin', ?string $systemMsgText = null): bool
    {
        if ($chat->status === 'ended') {
            return true;
        }

        $chat->update([
            'status'   => 'ended',
            'ended_at' => now(),
        ]);

        $defaultMsg = match ($endedBy) {
            'customer' => 'Percakapan telah diakhiri oleh Customer.',
            'system'   => 'Percakapan otomatis diakhiri karena tidak ada aktivitas.',
            default    => 'Percakapan diakhiri oleh Admin.',
        };

        LiveChatMessage::create([
            'live_chat_id' => $chat->id,
            'sender_type'  => 'system',
            'sender_name'  => 'System',
            'message'      => $systemMsgText ?? $defaultMsg,
        ]);

        // 1. Send Email Transcript to Customer & Admin
        $this->sendEmailTranscript($chat);

        // 2. Send Transcript via WhatsApp
        $this->sendWhatsAppTranscript($chat);

        return true;
    }

    /**
     * Send email transcript to customer and admin.
     */
    protected function sendEmailTranscript(LiveChat $chat): void
    {
        try {
            if (!empty($chat->customer_email)) {
                \Illuminate\Support\Facades\Mail::to($chat->customer_email)
                    ->send(new \App\Mail\LiveChatTranscriptMail($chat, 'customer'));
            }

            $adminEmail = \App\Models\Setting::get('mail.admin_email', config('mail.from.address'));
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)
                    ->send(new \App\Mail\LiveChatTranscriptMail($chat, 'admin'));
            }
        } catch (\Throwable $e) {
            Log::error("[LiveChatService] Failed to send email transcript: " . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp transcript.
     */
    protected function sendWhatsAppTranscript(LiveChat $chat): void
    {
        $messages = $chat->messages()->get();
        $dateStr = now()->translatedFormat('d M Y, H:i') . ' WIB';

        $transcriptLines = [];
        foreach ($messages as $m) {
            if ($m->sender_type === 'system') continue;
            $senderLabel = $m->sender_type === 'customer' ? $chat->customer_name : 'Admin Cooca';
            $timeStr = $m->created_at->format('H:i');
            $transcriptLines[] = "[{$timeStr}] {$senderLabel}: {$m->message}";
        }

        $transcriptText = implode("\n", $transcriptLines);

        $waTranscriptMessage = "📄 *RIWAYAT PERCAKAPAN LIVE CHAT — COOCA.ID*\n"
                             . "═════════════════════════\n"
                             . "📅 *Waktu*: {$dateStr}\n"
                             . "👤 *Customer*: {$chat->customer_name} (+{$chat->customer_phone})\n"
                             . ($chat->customer_email ? "📧 *Email*: {$chat->customer_email}\n\n" : "\n")
                             . "💬 *TRANSKRIP PERCAKAPAN*:\n"
                             . "─────────────────────────\n"
                             . "{$transcriptText}\n"
                             . "─────────────────────────\n\n"
                             . "Terima kasih telah berkonsultasi dengan *Cooca.id*! Sesi percakapan ini telah resmi diakhiri. Transkrip juga telah dikirimkan ke email Anda. 😊";

        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('status', 'connected')
            ->latest()
            ->first();

        if ($device && !empty($chat->customer_phone)) {
            try {
                $this->gatewayService->sendMessage($device->session_id, $chat->customer_phone, $waTranscriptMessage);
            } catch (\Throwable $e) {
                Log::error("[LiveChatService] Failed to send WA transcript: " . $e->getMessage());
            }
        }
    }
}
