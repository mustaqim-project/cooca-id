<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiPlanConfig;
use App\Models\AiProviderConfig;
use App\Models\AiUsageCycle;
use App\Models\AiUsageLog;
use App\Models\SubscriptionPlan;
use App\Services\Ai\Providers\AiProviderResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class AiDashboardController extends Controller
{
    public function __construct(
        private readonly AiProviderResolver $providerResolver,
    ) {}

    public function index(Request $request)
    {
        $currentMonthStart = now()->startOfMonth();

        // Monthly Usage Aggregates
        $monthlyUsage = AiUsageLog::select(
            DB::raw('SUM(total_tokens) as total_tokens'),
            DB::raw('SUM(cost_usd) as total_cost_usd'),
            DB::raw('COUNT(*) as total_requests'),
            DB::raw('AVG(duration_ms) as avg_latency_ms')
        )
        ->where('created_at', '>=', $currentMonthStart)
        ->first();

        // Provider Status Discovery
        $availableProviders = ['openai', 'anthropic', 'gemini', 'deepseek'];
        $dbProviders = AiProviderConfig::all()->keyBy('provider');

        $providers = [];
        $defaultUrls = [
            'openai' => 'https://api.openai.com/v1',
            'anthropic' => 'https://api.anthropic.com',
            'gemini' => 'https://generativelanguage.googleapis.com/v1beta',
            'deepseek' => 'https://api.deepseek.com/v1',
        ];

        foreach ($availableProviders as $pKey) {
            $conf = $dbProviders->get($pKey);
            $providers[$pKey] = [
                'provider' => $pKey,
                'is_configured' => $conf !== null,
                'is_active' => $conf ? $conf->is_active : false,
                'base_url' => $conf ? $conf->base_url : ($defaultUrls[$pKey] ?? ''),
                'has_key' => $conf && !empty($conf->api_key),
                'updated_at' => $conf ? $conf->updated_at : null,
            ];
        }

        // Subscription Plans with AI Config
        $plans = SubscriptionPlan::with('aiPlanConfig')->get();

        // Active Usage Cycles
        $activeCycles = AiUsageCycle::with(['license.customer', 'license.product'])
            ->where('cycle_end', '>=', now())
            ->where('cycle_start', '<=', now())
            ->latest()
            ->paginate(15);

        // Recent Usage Logs
        $recentLogs = AiUsageLog::with(['apiKey.customer', 'license'])
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.ai.dashboard', compact(
            'monthlyUsage',
            'providers',
            'plans',
            'activeCycles',
            'recentLogs'
        ));
    }

    public function saveProvider(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $validated = $request->validate([
            'provider' => 'required|string|in:openai,anthropic,gemini,deepseek',
            'api_key'  => 'nullable|string',
            'base_url' => 'required|url',
            'is_active' => 'nullable|boolean',
        ]);

        $config = AiProviderConfig::firstOrNew(['provider' => $validated['provider']]);
        
        if (!empty($validated['api_key'])) {
            $config->api_key = $validated['api_key'];
        }
        $config->base_url = $validated['base_url'];
        $config->is_active = $request->boolean('is_active', true);
        $config->save();

        return redirect()->route('admin.ai.dashboard')->with('success', "Konfigurasi AI Provider [{$validated['provider']}] berhasil disimpan.");
    }

    public function toggleProvider(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $validated = $request->validate([
            'provider' => 'required|string|in:openai,anthropic,gemini,deepseek',
        ]);

        $config = AiProviderConfig::where('provider', $validated['provider'])->first();
        if ($config) {
            $config->update(['is_active' => !$config->is_active]);
            $statusStr = $config->is_active ? 'Diaktifkan' : 'Dinonaktifkan';
            return redirect()->route('admin.ai.dashboard')->with('success', "Provider [{$config->provider}] berhasil {$statusStr}.");
        }

        return redirect()->route('admin.ai.dashboard')->with('error', "Provider belum dikonfigurasi.");
    }

    public function testProvider(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $validated = $request->validate([
            'provider' => 'required|string|in:openai,anthropic,gemini,deepseek',
        ]);

        $testModels = [
            'openai' => 'gpt-4o-mini',
            'anthropic' => 'claude-3-5-haiku-20241022',
            'gemini' => 'gemini-3.6-flash',
            'deepseek' => 'deepseek-chat',
        ];

        $model = $testModels[$validated['provider']] ?? 'gpt-4o-mini';

        try {
            $provider = $this->providerResolver->resolveFor($model);
            $res = $provider->chatCompletion([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => 'Ping! Return only the word "OK".'],
                ],
                'max_tokens' => 10,
            ]);

            return redirect()->route('admin.ai.dashboard')->with('success', "Test koneksi ke [{$validated['provider']}] BERHASIL! Model {$model} merespon dengan baik.");
        } catch (Throwable $e) {
            return redirect()->route('admin.ai.dashboard')->with('error', "Test koneksi ke [{$validated['provider']}] GAGAL: " . $e->getMessage());
        }
    }

    public function savePlanConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $validated = $request->validate([
            'subscription_plan_id' => 'required|uuid|exists:subscription_plans,id',
            'monthly_token_quota'  => 'required|integer|min:1000',
            'requests_per_minute'  => 'required|integer|min:5|max:600',
            'allowed_models'       => 'required|array|min:1',
            'overage_policy'       => 'required|string|in:hard_stop,soft_stop',
        ]);

        AiPlanConfig::updateOrCreate(
            ['subscription_plan_id' => $validated['subscription_plan_id']],
            [
                'monthly_token_quota' => $validated['monthly_token_quota'],
                'requests_per_minute' => $validated['requests_per_minute'],
                'allowed_models'      => $validated['allowed_models'],
                'overage_policy'      => $validated['overage_policy'],
            ]
        );

        return redirect()->route('admin.ai.dashboard')->with('success', 'Konfigurasi Kuota AI Plan berhasil diperbarui.');
    }

    public function grantBonus(Request $request, AiUsageCycle $cycle)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $validated = $request->validate([
            'bonus_tokens' => 'required|integer|min:1',
            'reason'       => 'required|string|max:255',
        ]);

        $cycle->increment('token_quota', $validated['bonus_tokens']);

        return redirect()->route('admin.ai.dashboard')->with('success', "Berhasil menambahkan bonus +" . number_format($validated['bonus_tokens']) . " token ke siklus ini.");
    }
}
