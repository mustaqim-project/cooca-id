<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AuditLogController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = AuditLog::query()->with(['user', 'model']);

        // Filter by Risk Level
        if ($request->filled('risk_level') && $request->input('risk_level') !== 'all') {
            $query->where('risk_level', $request->input('risk_level'));
        }

        // Filter by User Guard / Type
        if ($request->filled('user_type') && $request->input('user_type') !== 'all') {
            $query->where('user_type', $request->input('user_type'));
        }

        // Search across Action, IP, or Model Type
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%");
            });
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $auditLogs = $query->latest('created_at')->paginate(25)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $auditLogs,
            ]);
        }

        // Quick Stats
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', now()->today())->count(),
            'admin_actions' => AuditLog::where('user_type', 'admin')->count(),
            'customer_actions' => AuditLog::where('user_type', 'customer')->count(),
            'high_risk' => AuditLog::whereIn('risk_level', ['high', 'critical'])->count(),
        ];

        return view('admin.audit-logs.index', compact('auditLogs', 'stats'));
    }

    public function show(AuditLog $auditLog): View
    {
        $auditLog->load(['user', 'model']);
        
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
