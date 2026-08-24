<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiPlanConfig;
use App\Models\AiProviderConfig;
use App\Models\AiTokenPackage;
use App\Models\AiTokenPurchase;
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

        // Single Unified AI Provider Configuration
        $providerConfig = AiProviderConfig::first();
        if (!$providerConfig) {
            $providerConfig = new AiProviderConfig([
                'provider'          => 'primary',
                'base_url'          => 'https://r4g77gv.abc-tunnel.us/v1',
                'models'            => [
                    'cx/gpt-5.5-xhigh',
                    'cx/gpt-5.5',
                    'ag/claude-sonnet-4-6',
                    'ag/claude-opus-4-6-thinking',
                    'ag/gemini-pro-agent',
                ],
                'total_token_quota' => 10000000,
                'is_active'         => true,
            ]);
            $hasKey = false;
        } else {
            $hasKey = !empty($providerConfig->api_key);
        }

        $availableModels = $providerConfig->getModelsList();

        // Master Gateway Token Tracking
        $allTimeTokensUsed = (int) AiUsageLog::sum('total_tokens');
        $masterQuota = (int) ($providerConfig->total_token_quota ?? 0);
        $masterRemaining = $masterQuota > 0 ? max(0, $masterQuota - $allTimeTokensUsed) : null;
        $masterPercentUsed = $masterQuota > 0 ? min(100, round(($allTimeTokensUsed / $masterQuota) * 100, 1)) : 0;

        // Subscription Plans with AI Config
        $plans = SubscriptionPlan::with('aiPlanConfig')->get();

        // Active Usage Cycles
        $activeCycles = AiUsageCycle::with(['license.customer', 'license.product'])
            ->where('cycle_end', '>=', now())
            ->where('cycle_start', '<=', now())
            ->latest()
            ->paginate(15);

        // AI Token Packages & Recent Purchases
        $tokenPackages = AiTokenPackage::orderBy('sort_order', 'asc')->get();
        $recentPurchases = AiTokenPurchase::with(['customer', 'license.product', 'package', 'transaction'])
            ->latest()
            ->paginate(15);

        // Recent Usage Logs
        $recentLogs = AiUsageLog::with(['apiKey.customer', 'license'])
            ->latest('created_at')
            ->limit(25)
            ->get();

        return view('admin.ai.dashboard', compact(
            'monthlyUsage',
            'providerConfig',
            'hasKey',
            'availableModels',
            'allTimeTokensUsed',
            'masterQuota',
            'masterRemaining',
            'masterPercentUsed',
            'plans',
            'activeCycles',
            'recentLogs',
            'tokenPackages',
            'recentPurchases'
        ));
    }

    public function saveProvider(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        if ($request->has('total_token_quota')) {
            $request->merge([
                'total_token_quota' => (int) str_replace(['.', ',', ' '], '', (string) $request->input('total_token_quota', 0)),
            ]);
        }

        $validated = $request->validate([
            'base_url'          => 'required|url',
            'api_key'           => 'nullable|string',
            'models'            => 'nullable|string',
            'total_token_quota' => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        // Parse models string (newline, comma, semicolon separated)
        $modelsInput = $validated['models'] ?? '';
        $models = [];
        if (!empty($modelsInput)) {
            $parts = preg_split('/[\r\n,;]+/', (string) $modelsInput, -1, PREG_SPLIT_NO_EMPTY);
            $models = array_values(array_unique(array_filter(array_map('trim', $parts))));
        }

        if (empty($models)) {
            $models = [
                'cx/gpt-5.5-xhigh',
                'cx/gpt-5.5',
                'ag/claude-sonnet-4-6',
                'ag/claude-opus-4-6-thinking',
                'ag/gemini-pro-agent',
            ];
        }

        $config = AiProviderConfig::first();
        if (!$config) {
            $config = new AiProviderConfig();
            $config->provider = 'primary';
        }

        if (!empty($validated['api_key'])) {
            $config->api_key = trim($validated['api_key']);
        }
        $config->base_url = rtrim(trim($validated['base_url']), '/');
        $config->models = $models;
        $config->total_token_quota = (int) ($validated['total_token_quota'] ?? 0);
        $config->is_active = $request->boolean('is_active', true);
        $config->save();

        return redirect()->route('admin.ai.dashboard')->with('success', "Konfigurasi AI Gateway & Kuota Master berhasil disimpan.");
    }

    public function toggleProvider(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $config = AiProviderConfig::first();
        if ($config) {
            $config->update(['is_active' => !$config->is_active]);
            $statusStr = $config->is_active ? 'Diaktifkan' : 'Dinonaktifkan';
            return redirect()->route('admin.ai.dashboard')->with('success', "AI Gateway berhasil {$statusStr}.");
        }

        return redirect()->route('admin.ai.dashboard')->with('error', "AI Gateway belum dikonfigurasi.");
    }

    public function testProvider(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $config = AiProviderConfig::first();
        if (!$config || empty($config->base_url)) {
            return redirect()->route('admin.ai.dashboard')->with('error', "Base URL AI Gateway belum diatur.");
        }

        $models = $config->getModelsList();
        $testModel = $request->input('model', $models[0] ?? 'cx/gpt-5.5-xhigh');

        $started = microtime(true);
        try {
            $provider = $this->providerResolver->resolveFor($testModel);
            $res = $provider->chatCompletion([
                'model' => $testModel,
                'messages' => [
                    ['role' => 'user', 'content' => 'Ping! Return only the word "OK".'],
                ],
                'max_tokens' => 10,
            ]);

            $durationMs = round((microtime(true) - $started) * 1000);

            return redirect()->route('admin.ai.dashboard')->with('success', "Test koneksi AI Gateway BERHASIL! Model [{$testModel}] merespon dalam {$durationMs}ms.");
        } catch (Throwable $e) {
            return redirect()->route('admin.ai.dashboard')->with('error', "Test koneksi AI Gateway GAGAL: " . $e->getMessage());
        }
    }

    public function savePlanConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        if ($request->has('monthly_token_quota')) {
            $request->merge([
                'monthly_token_quota' => (int) str_replace(['.', ',', ' '], '', (string) $request->input('monthly_token_quota')),
            ]);
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

        if ($request->has('bonus_tokens')) {
            $request->merge([
                'bonus_tokens' => (int) str_replace(['.', ',', ' '], '', (string) $request->input('bonus_tokens')),
            ]);
        }

        $validated = $request->validate([
            'bonus_tokens' => 'required|integer|min:1',
            'reason'       => 'required|string|max:255',
        ]);

        $cycle->increment('token_quota', $validated['bonus_tokens']);

        return redirect()->route('admin.ai.dashboard')->with('success', "Berhasil menambahkan bonus +" . number_format($validated['bonus_tokens']) . " token ke siklus ini.");
    }

    public function savePackage(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        if ($request->has('token_amount')) {
            $request->merge([
                'token_amount' => (int) str_replace(['.', ',', ' '], '', (string) $request->input('token_amount')),
            ]);
        }
        if ($request->has('price')) {
            $request->merge([
                'price' => (float) str_replace(['.', ',', ' '], '', (string) $request->input('price')),
            ]);
        }

        $validated = $request->validate([
            'id'           => 'nullable|uuid|exists:ai_token_packages,id',
            'name'         => 'required|string|max:100',
            'token_amount' => 'required|integer|min:1000',
            'price'        => 'required|numeric|min:0',
            'description'  => 'nullable|string|max:255',
            'badge'        => 'nullable|string|max:50',
            'sort_order'   => 'required|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $package = !empty($validated['id']) ? AiTokenPackage::find($validated['id']) : new AiTokenPackage();
        $package->name = $validated['name'];
        $package->token_amount = $validated['token_amount'];
        $package->price = $validated['price'];
        $package->description = $validated['description'] ?? null;
        $package->badge = $validated['badge'] ?? null;
        $package->sort_order = $validated['sort_order'];
        $package->is_active = $request->boolean('is_active', true);
        $package->save();

        $msg = !empty($validated['id']) ? 'Paket token berhasil diperbarui.' : 'Paket token baru berhasil ditambahkan.';
        return redirect()->route('admin.ai.dashboard')->with('success', $msg);
    }

    public function togglePackage(Request $request, AiTokenPackage $package)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $package->update(['is_active' => !$package->is_active]);
        $statusStr = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.ai.dashboard')->with('success', "Paket [{$package->name}] berhasil {$statusStr}.");
    }

    public function deletePackage(Request $request, AiTokenPackage $package)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.ai.dashboard');
        }

        $package->delete();
        return redirect()->route('admin.ai.dashboard')->with('success', "Paket [{$package->name}] berhasil dihapus.");
    }
}
