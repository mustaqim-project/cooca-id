<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveChat;
use App\Models\LiveChatMessage;
use App\Models\WhatsAppDevice;
use App\Services\LiveChat\LiveChatService;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class AdminLiveChatController extends Controller
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
     * Display Admin Live Chat Dashboard.
     */
    public function index()
    {
        $this->liveChatService->autoEndInactiveChats();

        $chats = LiveChat::with(['messages' => function($q) {
            $q->orderBy('id', 'desc');
        }])
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        // Fetch templates managed by Admin from LiveChatTemplate model
        $templatesList = \App\Models\LiveChatTemplate::active()->ordered()->get();

        return view('admin.live-chats.index', [
            'chats'         => $chats,
            'templatesList' => $templatesList,
        ]);
    }

    /**
     * Get updated chat sessions list for AJAX realtime polling.
     */
    public function getSessions()
    {
        $this->liveChatService->autoEndInactiveChats();

        $chats = LiveChat::with(['messages' => function($q) {
            $q->orderBy('id', 'desc');
        }])
        ->orderBy('updated_at', 'desc')
        ->get();

        $sessionData = $chats->map(function($chat) {
            return [
                'id'             => $chat->id,
                'customer_name'  => $chat->customer_name,
                'customer_phone' => $chat->customer_phone,
                'status'         => $chat->status,
                'last_message'   => $chat->messages->first()?->message ?? 'Belum ada pesan',
                'updated_at_fmt' => $chat->updated_at ? $chat->updated_at->diffForHumans() : '',
            ];
        });

        return response()->json([
            'success' => true,
            'chats'   => $sessionData,
        ]);
    }

    /**
     * Get messages for a specific chat (AJAX).
     */
    public function getMessages($id)
    {
        $this->liveChatService->autoEndInactiveChats();

        $chat = LiveChat::with('messages')->findOrFail($id);
        return response()->json([
            'success' => true,
            'chat'    => $chat,
            'messages'=> $chat->messages,
        ]);
    }

    /**
     * Admin replies to customer in live chat (AJAX).
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = LiveChat::findOrFail($id);

        if ($chat->status === 'ended') {
            return response()->json(['success' => false, 'error' => 'Percakapan sudah diakhiri.'], 400);
        }

        $messageText = trim($request->input('message'));

        $msg = LiveChatMessage::create([
            'live_chat_id' => $chat->id,
            'sender_type'  => 'admin',
            'sender_name'  => 'Admin Cooca',
            'message'      => $messageText,
        ]);

        $chat->touch();

        // Also send Admin's reply to Customer's WhatsApp directly
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('status', 'connected')
            ->latest()
            ->first();

        if ($device) {
            try {
                $waText = "💬 *Pesan Balasan dari Admin Cooca.id*:\n\n{$messageText}\n\n_(Anda juga dapat terus membalas di widget chat website)_";
                $this->gatewayService->sendMessage($device->session_id, $chat->customer_phone, $waText);
            } catch (\Throwable $e) {
                Log::error("[AdminLiveChatController] Failed to send Admin reply via WA: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
        ]);
    }

    /**
     * Admin ends live chat and triggers WA transcript.
     */
    public function endChat($id)
    {
        $chat = LiveChat::findOrFail($id);

        if ($chat->status === 'ended') {
            return response()->json(['success' => true, 'message' => 'Sesi sudah diakhiri.']);
        }

        $this->liveChatService->endChatSession($chat, 'admin');

        return response()->json([
            'success' => true,
            'message' => 'Sesi percakapan diakhiri & transkrip telah terkirim ke WA customer.',
        ]);
    }
}

