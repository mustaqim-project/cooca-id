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
     * Automatically end live chat sessions that have been inactive for >= 2 minutes (120 seconds).
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

            if ($lastActivityTime && $lastActivityTime->lte(now()->subMinutes(2))) {
                $this->endChatSession(
                    $chat,
                    'system',
                    'Percakapan otomatis diakhiri karena tidak ada aktivitas selama 2 menit.'
                );
                $endedCount++;
            }
        }

        return $endedCount;
    }

    /**
     * End a live chat session and send WhatsApp transcript.
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
            'system'   => 'Percakapan otomatis diakhiri karena tidak ada aktivitas selama 2 menit.',
            default    => 'Percakapan diakhiri oleh Admin.',
        };

        LiveChatMessage::create([
            'live_chat_id' => $chat->id,
            'sender_type'  => 'system',
            'sender_name'  => 'System',
            'message'      => $systemMsgText ?? $defaultMsg,
        ]);

        // Send Transcript via WA
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
                             . "👤 *Customer*: {$chat->customer_name} (+{$chat->customer_phone})\n\n"
                             . "💬 *TRANSKRIP PERCAKAPAN*:\n"
                             . "─────────────────────────\n"
                             . "{$transcriptText}\n"
                             . "─────────────────────────\n\n"
                             . "Terima kasih telah berkonsultasi dengan *Cooca.id*! Sesi percakapan ini telah resmi diakhiri. 😊";

        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('status', 'connected')
            ->latest()
            ->first();

        if ($device) {
            try {
                $this->gatewayService->sendMessage($device->session_id, $chat->customer_phone, $waTranscriptMessage);
            } catch (\Throwable $e) {
                Log::error("[LiveChatService] Failed to send WA transcript: " . $e->getMessage());
            }
        }

        return true;
    }
}
