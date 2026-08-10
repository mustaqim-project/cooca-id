<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LiveChat;
use App\Models\LiveChatMessage;
use App\Models\WhatsAppDevice;
use App\Services\LiveChat\LiveChatService;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

final class LiveChatController extends Controller
{
    protected WhatsAppGatewayService $gatewayService;
    protected LiveChatService $liveChatService;

    public function __construct(
        WhatsAppGatewayService $gatewayService,
        LiveChatService $liveChatService
    ) {
        $this->gatewayService = $gatewayService;
        $this->liveChatService = $liveChatService;
    }

    /**
     * Get Interactive Select Options / FAQs managed from Admin Panel.
     */
    public function getOptions()
    {
        $faqs = \App\Models\Faq::where('is_active', true)->orderBy('order')->get();

        $options = [];
        foreach ($faqs as $f) {
            $options[] = [
                'id'       => $f->id,
                'question' => $f->question,
                'answer'   => $f->answer,
                'category' => $f->category,
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $options,
        ]);
    }

    /**
     * Start a new live chat session from the website widget.
     */
    public function start(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:25',
            'message' => 'required|string|max:1000',
        ], [
            'name.required'    => 'Harap isi nama lengkap Anda.',
            'phone.required'   => 'Harap isi nomor WhatsApp Anda.',
            'message.required' => 'Harap tuliskan pesan awal Anda.',
        ]);

        $name = trim($request->input('name'));
        $phoneInput = trim($request->input('phone'));
        $messageText = trim($request->input('message'));

        $cleanPhone = preg_replace('/\D/', '', $phoneInput);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $token = 'lc_' . Str::random(32);

        $liveChat = LiveChat::create([
            'session_token'  => $token,
            'customer_name'  => $name,
            'customer_phone' => $cleanPhone,
            'status'         => 'active',
        ]);

        // Add initial customer message
        $initialMsg = LiveChatMessage::create([
            'live_chat_id' => $liveChat->id,
            'sender_type'  => 'customer',
            'sender_name'  => $name,
            'message'      => $messageText,
        ]);

        // Find active Admin WhatsApp Device
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('status', 'connected')
            ->latest()
            ->first();

        if ($device) {
            try {
                // 1. Send automated welcome & instructions to Customer WhatsApp
                $welcomeText = "Halo *{$name}*! 👋\n\nSesi *Live Chat Website Cooca.id* Anda telah aktif.\n\nPesan awal Anda:\n\"_{$messageText}_\"\n\nTim Admin kami sedang membalas percakapan Anda secara *realtime di widget website*. Setelah percakapan selesai, *riwayat lengkap (transkrip chat)* akan otomatis dikirimkan ke nomor WhatsApp ini! 😊";
                $this->gatewayService->sendMessage($device->session_id, $cleanPhone, $welcomeText);

                // 2. Alert Admin Mobile WA with link to Admin Live Chat Panel
                if ($device->phone_number && $device->phone_number !== $cleanPhone) {
                    $adminMsg = "🔔 *PERCAKAPAN LIVE CHAT BARU*\n\n"
                        . "👤 *Customer*: {$name}\n"
                        . "📱 *No. WA*: +{$cleanPhone}\n"
                        . "💬 *Pesan*: {$messageText}\n\n"
                        . "👉 *Balas Realtime di Admin Panel*:\n" . route('admin.live-chats.index');

                    $this->gatewayService->sendMessage($device->session_id, $device->phone_number, $adminMsg);
                }
            } catch (\Throwable $e) {
                Log::error("[LiveChatController] Failed to send initial WA notifications: " . $e->getMessage());
            }
        }

        return response()->json([
            'success'       => true,
            'session_token' => $token,
            'live_chat_id'  => $liveChat->id,
            'customer'      => [
                'name'  => $name,
                'phone' => $cleanPhone,
            ],
            'messages'      => [$initialMsg],
        ]);
    }

    /**
     * Poll messages for active session.
     */
    public function getMessages(Request $request)
    {
        $this->liveChatService->autoEndInactiveChats();

        $token = $request->input('session_token');
        $lastId = (int) $request->input('last_id', 0);

        $liveChat = LiveChat::where('session_token', $token)->first();

        if (!$liveChat) {
            return response()->json(['success' => false, 'error' => 'Sesi percakapan tidak ditemukan.'], 404);
        }

        $messages = LiveChatMessage::where('live_chat_id', $liveChat->id)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success'  => true,
            'status'   => $liveChat->status,
            'messages' => $messages,
        ]);
    }

    /**
     * Customer sends a new message in active live chat.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_token' => 'required|string',
            'message'       => 'required|string|max:1000',
        ]);

        $token = $request->input('session_token');
        $messageText = trim($request->input('message'));

        $liveChat = LiveChat::where('session_token', $token)->first();

        if (!$liveChat || $liveChat->status === 'ended') {
            return response()->json(['success' => false, 'error' => 'Sesi chat telah berakhir.'], 400);
        }

        $msg = LiveChatMessage::create([
            'live_chat_id' => $liveChat->id,
            'sender_type'  => 'customer',
            'sender_name'  => $liveChat->customer_name,
            'message'      => $messageText,
        ]);

        $liveChat->touch();

        return response()->json([
            'success' => true,
            'message' => $msg,
        ]);
    }

    /**
     * End Live Chat and send transcript to Customer's WhatsApp.
     */
    public function endChat(Request $request)
    {
        $token = $request->input('session_token');
        $liveChat = LiveChat::where('session_token', $token)->first();

        if (!$liveChat) {
            return response()->json(['success' => false, 'error' => 'Sesi percakapan tidak ditemukan.'], 404);
        }

        if ($liveChat->status === 'ended') {
            return response()->json(['success' => true, 'message' => 'Sesi sudah diakhiri sebelumnya.']);
        }

        $this->liveChatService->endChatSession($liveChat, 'customer');

        return response()->json([
            'success' => true,
            'message' => 'Percakapan berhasil diakhiri dan riwayat telah dikirim ke WhatsApp Anda.',
        ]);
    }

    /**
     * Backwards-compatible wrapper for route expecting `end`.
     */
    public function end(Request $request)
    {
        return $this->endChat($request);
    }
}
