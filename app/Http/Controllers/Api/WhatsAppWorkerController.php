<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsAppWorkerController extends Controller
{
    private function authenticate(Request $request)
    {
        $token = $request->bearerToken();
        $expectedToken = env('WA_WORKER_TOKEN', 'secret-worker-token');
        if (!$token || $token !== $expectedToken) {
            abort(401, 'Unauthorized Worker');
        }
    }

    public function getQueue(Request $request)
    {
        $this->authenticate($request);

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
