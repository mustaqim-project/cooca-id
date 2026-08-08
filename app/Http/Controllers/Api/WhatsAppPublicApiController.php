<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppDevice;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\Request;

class WhatsAppPublicApiController extends Controller
{
    protected WhatsAppGatewayService $gatewayService;

    public function __construct(WhatsAppGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Endpoint Publik Pengiriman Pesan WhatsApp Fonnte-Style
     * Header: X-WA-API-KEY atau Authorization: Bearer wapi_sec_xxx
     */
    public function send(Request $request)
    {
        $apiKey = $request->header('X-WA-API-KEY') 
            ?? str_replace('Bearer ', '', $request->header('Authorization', ''))
            ?? $request->input('api_key');

        if (!$apiKey) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Missing X-WA-API-KEY header or Bearer token'
            ], 401);
        }

        $device = WhatsAppDevice::where('api_key', $apiKey)->first();

        if (!$device) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Invalid WhatsApp API Key'
            ], 401);
        }

        $request->validate([
            'target' => 'required|string',
            'message' => 'nullable|string',
            'url' => 'nullable|url',
        ]);

        $target = $request->input('target') ?? $request->input('phone') ?? $request->input('number');
        $message = $request->input('message') ?? $request->input('text') ?? '';
        $mediaUrl = $request->input('url') ?? $request->input('media_url') ?? $request->input('file');
        $options = array_filter([
            'filename' => $request->input('filename') ?? $request->input('file_name'),
            'type' => $request->input('type'),
            'ptt' => $request->input('ptt'),
            'location' => $request->input('location'),
            'vcard' => $request->input('vcard'),
        ]);

        if (!$message && !$mediaUrl && empty($options['location']) && empty($options['vcard'])) {
            return response()->json([
                'status' => false,
                'message' => 'Missing message or url/file parameter'
            ], 400);
        }

        $result = $this->gatewayService->sendMessage($device->session_id, $target, $message, $mediaUrl, $options);


        if (!empty($result['success'])) {
            return response()->json([
                'status' => true,
                'device' => $device->name,
                'target' => $target,
                'message' => 'Message queued / sent successfully',
                'details' => $result['result'] ?? null
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to send message: ' . ($result['error'] ?? 'Unknown error'),
            'details' => $result
        ], 500);
    }
}
