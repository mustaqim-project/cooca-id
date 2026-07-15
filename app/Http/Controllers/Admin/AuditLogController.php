<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->with(['user', 'model']);

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->input('risk_level'));
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->input('action') . '%');
        }

        $auditLogs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.audit-logs.index', compact('auditLogs'));
    }

    public function show(AuditLog $auditLog): View
    {
        $auditLog->load(['user', 'model']);
        
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
