<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LiveChat;
use App\Models\LiveChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class WhatsAppWebhookController extends Controller
{
    /**
     * Handle incoming & outgoing WA Webhook events from Node wa-server.
     */
    public function handle(Request $request)
    {
        $senderRaw = $request->input('sender'); // e.g. "6282114468467@s.whatsapp.net" or "6282114468467"
        $fromMe = (bool) $request->input('fromMe');
        $messageText = trim($request->input('message') ?? '');
        $pushName = $request->input('pushName') ?: 'WhatsApp User';

        if (!$senderRaw || !$messageText) {
            return response()->json(['status' => 'ignored', 'reason' => 'empty message or sender']);
        }

        // Clean sender phone number to standard 628xxx format
        $cleanPhone = preg_replace('/\D/', '', explode('@', $senderRaw)[0]);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        // Find active live chat session matching this customer phone
        $liveChat = LiveChat::where('customer_phone', $cleanPhone)
            ->where('status', 'active')
            ->latest()
            ->first();

        if ($liveChat) {
            // Determine sender_type: if fromMe is true, Admin sent from Mobile WA app!
            $senderType = $fromMe ? 'admin' : 'customer';
            $senderName = $fromMe ? 'Admin Cooca' : ($liveChat->customer_name ?: $pushName);

            // Avoid duplicate message entry if exact message was inserted milliseconds ago
            $existing = LiveChatMessage::where('live_chat_id', $liveChat->id)
                ->where('message', $messageText)
                ->where('sender_type', $senderType)
                ->where('created_at', '>=', now()->subSeconds(3))
                ->first();

            if (!$existing) {
                LiveChatMessage::create([
                    'live_chat_id' => $liveChat->id,
                    'sender_type'  => $senderType,
                    'sender_name'  => $senderName,
                    'message'      => $messageText,
                ]);

                $liveChat->touch();
            }

            return response()->json(['status' => 'processed', 'live_chat_id' => $liveChat->id, 'sender_type' => $senderType]);
        }

        return response()->json(['status' => 'no_active_live_chat_found']);
    }
}
