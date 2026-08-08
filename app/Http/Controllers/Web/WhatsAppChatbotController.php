<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppDevice;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class WhatsAppChatbotController extends Controller
{
    protected WhatsAppGatewayService $gatewayService;

    public function __construct(WhatsAppGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Handle Chatbot WhatsApp submission from website floating widget.
     */
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:25',
            'message' => 'required|string|max:1000',
        ], [
            'name.required'    => 'Harap isi nama lengkap Anda.',
            'phone.required'   => 'Harap isi nomor WhatsApp Anda.',
            'message.required' => 'Harap tuliskan pesan Anda.',
        ]);

        $name = trim($request->input('name'));
        $phoneInput = trim($request->input('phone'));
        $message = trim($request->input('message'));

        // Clean & format customer phone number to standard 628xxx format
        $cleanPhone = preg_replace('/\D/', '', $phoneInput);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        // Find active Admin WhatsApp Device
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('status', 'connected')
            ->latest()
            ->first();

        // Fallback if no admin device connected yet
        if (!$device) {
            $device = WhatsAppDevice::where('owner_type', 'admin')->latest()->first();
        }

        $adminPhone = $device?->phone_number ?? '6282114468467';
        $cleanAdminPhone = preg_replace('/\D/', '', $adminPhone);
        if (str_starts_with($cleanAdminPhone, '0')) {
            $cleanAdminPhone = '62' . substr($cleanAdminPhone, 1);
        }

        // Build WhatsApp direct chat link for customer
        $directText = urlencode("Halo Tim Cooca.id, nama saya *{$name}* ({$phoneInput}).\n\nPesan saya:\n{$message}");
        $waLink = "https://wa.me/{$cleanAdminPhone}?text={$directText}";

        // If active connected device is available, trigger automated 2-way notification
        if ($device && $device->status === 'connected') {
            try {
                // 1. Send automated welcome & confirmation message to Customer's WhatsApp
                $customerMsg = "Halo *{$name}*! 👋\n\nTerima kasih telah menghubungi *Cooca.id*.\n\nPesan Anda:\n\"_{$message}_\"\n\nTim Support/Admin kami telah menerima pesan Anda dan akan segera membalas percakapan di nomor WhatsApp ini. Silakan sampaikan pertanyaan tambahan Anda di sini! 😊";
                $this->gatewayService->sendMessage($device->session_id, $cleanPhone, $customerMsg);

                // 2. Send instant chat alert notification to Admin's mobile WhatsApp
                if ($device->phone_number && $device->phone_number !== $cleanPhone) {
                    $replyLink = "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo {$name}, terima kasih telah menghubungi Cooca.id. Ada yang bisa kami bantu?");
                    $adminMsg = "🔔 *PESAN WEBSITE CHATBOT BARU*\n\n"
                              . "👤 *Nama*: {$name}\n"
                              . "📱 *No. WA*: +{$cleanPhone}\n"
                              . "💬 *Pesan*: {$message}\n\n"
                              . "👉 *KLIK UNTUK BALAS LANGSUNG KE HP CUSTOMER*:\n{$replyLink}";
                    
                    $this->gatewayService->sendMessage($device->session_id, $device->phone_number, $adminMsg);
                }
            } catch (\Throwable $e) {
                Log::error("[WhatsAppChatbotController] Failed to send automated WA message: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda telah berhasil dikirim ke Admin WhatsApp!',
            'name'    => $name,
            'phone'   => $cleanPhone,
            'wa_link' => $waLink,
        ]);
    }
}
