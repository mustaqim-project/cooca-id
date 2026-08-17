<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiUsageCycle;
use App\Models\AiUsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class AiDashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentMonthStart = now()->startOfMonth();

        // Admin dashboard margin & usage per customer
        $monthlyUsage = AiUsageLog::select(
            DB::raw('SUM(total_tokens) as total_tokens'),
            DB::raw('SUM(cost_usd) as total_cost_usd')
        )
        ->where('created_at', '>=', $currentMonthStart)
        ->first();

        $activeCycles = AiUsageCycle::with(['license.customer', 'license.product'])
            ->where('cycle_end', '>=', now())
            ->where('cycle_start', '<=', now())
            ->paginate(25);

        return view('admin.ai.dashboard', compact('monthlyUsage', 'activeCycles'));
    }

    public function grantBonus(Request $request, AiUsageCycle $cycle)
    {
        $validated = $request->validate([
            'bonus_tokens' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $cycle->increment('token_quota', $validated['bonus_tokens']);
        
        // In a real application, you might want to log the reason or store bonus tokens separately
        // to audit them properly.

        return back()->with('success', "Granted {$validated['bonus_tokens']} bonus tokens to cycle.");
    }
}
