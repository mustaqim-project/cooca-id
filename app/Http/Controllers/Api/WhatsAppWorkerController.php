<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsAppWorkerController extends Controller
{
    private function authenticate(Request $request)
    {
        $token = $request->bearerToken();
        $expectedToken = config('services.wa_worker.token');
        
        if (empty($expectedToken)) {
            \Illuminate\Support\Facades\Log::critical('WA_WORKER_TOKEN is not configured — worker endpoints are unreachable.');
            abort(500, 'Worker authentication is not configured');
        }

        if (!$token || !hash_equals($expectedToken, $token)) {
            abort(401, 'Unauthorized Worker');
        }
    }

    public function getQueue(Request $request)
    {
        $this->authenticate($request);

        // Return empty queue if WhatsApp is globally disabled
        if (! (bool) \App\Models\Setting::get('whatsapp.notifications_active', true)) {
            return response()->json([
                'success' => true,
                'data'    => [],
            ]);
        }

        $queues = \App\Models\WhatsAppQueue::where('status', 'pending')
            ->orderBy('id', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $queues,
        ]);
    }

    public function updateQueue(Request $request)
    {
        $this->authenticate($request);

        $request->validate([
            'id'     => 'required|integer',
            'status' => 'required|in:sent,failed',
        ]);

        $queue = \App\Models\WhatsAppQueue::find($request->id);
        if ($queue) {
            $queue->update([
                'status' => $request->status,
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }
}
