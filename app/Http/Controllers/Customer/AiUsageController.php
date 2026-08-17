<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AiApiKey;
use App\Models\AiUsageCycle;
use App\Models\AiUsageLog;
use App\Models\License;
use App\Services\Ai\AiApiKeyService;
use App\Services\Ai\AiQuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AiUsageController extends Controller
{
    public function __construct(
        private readonly AiApiKeyService $apiKeyService,
        private readonly AiQuotaService $quotaService,
    ) {}

    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user() ?? Auth::user();

        // Customer's Active Licenses
        $licenses = License::with(['product', 'subscriptionPlan.aiPlanConfig'])
            ->where('customer_id', $customer->getKey())
            ->where('status', License::STATUS_ACTIVE)
            ->get();

        $licenseIds = $licenses->pluck('id')->toArray();

        // API Keys
        $keys = AiApiKey::with('license.product')
            ->where('customer_id', $customer->getKey())
            ->latest()
            ->get();

        // Active Usage Cycles for these licenses
        $cycles = AiUsageCycle::with('license.product')
            ->whereIn('license_id', $licenseIds)
            ->where('cycle_start', '<=', now())
            ->where('cycle_end', '>=', now())
            ->get()
            ->keyBy('license_id');

        // Recent Usage Logs
        $recentLogs = AiUsageLog::whereIn('license_id', $licenseIds)
            ->latest()
            ->limit(25)
            ->get();

        // Aggregates for current month
        $currentMonthUsage = AiUsageLog::whereIn('license_id', $licenseIds)
            ->where('created_at', '>=', now()->startOfMonth())
            ->selectRaw('SUM(total_tokens) as total_tokens, COUNT(*) as total_requests, AVG(duration_ms) as avg_latency')
            ->first();

        return view('customer.ai.usage', compact(
            'customer',
            'licenses',
            'keys',
            'cycles',
            'recentLogs',
            'currentMonthUsage'
        ));
    }

    public function createKey(Request $request)
    {
        $customer = Auth::guard('customer')->user() ?? Auth::user();

        $validated = $request->validate([
            'license_id' => 'required|uuid|exists:licenses,id',
            'name'       => 'required|string|max:64',
        ]);

        $license = License::where('id', $validated['license_id'])
            ->where('customer_id', $customer->getKey())
            ->where('status', License::STATUS_ACTIVE)
            ->firstOrFail();

        $result = $this->apiKeyService->issueForLicense($license, $validated['name']);

        return back()->with('new_api_key', [
            'plain_key' => $result['plain_key'],
            'name'      => $result['model']->name,
            'prefix'    => $result['model']->key_prefix,
        ])->with('success', 'AI API Key baru berhasil dibuat!');
    }

    public function revokeKey(Request $request, AiApiKey $key)
    {
        $customer = Auth::guard('customer')->user() ?? Auth::user();

        if ($key->customer_id !== $customer->getKey()) {
            abort(403, 'Unauthorized action.');
        }

        $this->apiKeyService->revoke($key);

        return back()->with('success', "API Key [{$key->name}] berhasil dinonaktifkan (revoked).");
    }
}
