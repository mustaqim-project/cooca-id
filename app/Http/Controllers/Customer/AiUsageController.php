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

        $apiKeyIds = $keys->pluck('id')->toArray();

        // Recent Usage Logs for this customer
        $recentLogs = AiUsageLog::with('apiKey')
            ->where(function ($query) use ($licenseIds, $apiKeyIds) {
                $query->whereIn('license_id', $licenseIds)
                      ->orWhereIn('ai_api_key_id', $apiKeyIds);
            })
            ->latest('created_at')
            ->paginate(25);

        // Aggregates for current month
        $currentMonthUsage = AiUsageLog::where(function ($query) use ($licenseIds, $apiKeyIds) {
                $query->whereIn('license_id', $licenseIds)
                      ->orWhereIn('ai_api_key_id', $apiKeyIds);
            })
            ->where('created_at', '>=', now()->startOfMonth())
            ->selectRaw('SUM(total_tokens) as total_tokens, COUNT(*) as total_requests, AVG(duration_ms) as avg_latency')
            ->first();

        // AI Token Packages
        $tokenPackages = \App\Models\AiTokenPackage::active()->get();

        return view('customer.ai.usage', compact(
            'customer',
            'licenses',
            'keys',
            'cycles',
            'recentLogs',
            'currentMonthUsage',
            'tokenPackages'
        ));
    }

    public function purchasePackage(Request $request)
    {
        $customer = Auth::guard('customer')->user() ?? Auth::user();

        $validated = $request->validate([
            'license_id' => 'required|uuid|exists:licenses,id',
            'package_id' => 'required|uuid|exists:ai_token_packages,id',
        ]);

        $license = License::where('id', $validated['license_id'])
            ->where('customer_id', $customer->getKey())
            ->where('status', License::STATUS_ACTIVE)
            ->firstOrFail();

        $package = \App\Models\AiTokenPackage::where('id', $validated['package_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $invoice = \Illuminate\Support\Facades\DB::transaction(function () use ($customer, $license, $package) {
            $invoiceNumber = 'INV-AI-' . strtoupper(date('Ymd')) . '-' . strtoupper(\Illuminate\Support\Str::random(6));

            $transaction = \App\Models\Transaction::create([
                'customer_id'      => $customer->getKey(),
                'subscription_id'  => $license->subscription_id ?? null,
                'type'             => 'ai_token_topup',
                'description'      => "Top-Up Kuota Token AI: {$package->name} (+" . number_format($package->token_amount) . " Token)",
                'invoice_number'   => $invoiceNumber,
                'gross_amount'     => $package->price,
                'voucher_discount' => 0,
                'net_amount'       => $package->price,
                'payment_method'   => 'pending',
                'payment_gateway'  => 'midtrans',
                'status'           => 'pending',
            ]);

            $invoice = \App\Models\Invoice::create([
                'transaction_id' => $transaction->id,
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $customer->getKey(),
                'amount'         => $package->price,
                'status'         => 'issued',
                'issued_at'      => now(),
                'due_at'         => now()->addDays(3),
            ]);

            \App\Models\AiTokenPurchase::create([
                'customer_id'         => $customer->getKey(),
                'license_id'          => $license->id,
                'ai_token_package_id' => $package->id,
                'transaction_id'      => $transaction->id,
                'tokens_amount'       => $package->token_amount,
                'price_paid'          => $package->price,
                'status'              => 'pending',
            ]);

            return $invoice;
        });

        return redirect()->route('customer.invoices.show', $invoice->id)->with('success', "Tagihan #{$invoice->invoice_number} untuk {$package->name} berhasil dibuat. Silakan pilih metode pembayaran Anda.");
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
