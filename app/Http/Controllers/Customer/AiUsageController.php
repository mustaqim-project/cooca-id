<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AiApiKey;
use App\Models\AiTokenLot;
use App\Models\AiTokenPackage;
use App\Models\AiTokenTransaction;
use App\Models\AiUsageLog;
use App\Models\License;
use App\Services\Ai\AiApiKeyService;
use App\Services\Ai\AiQuotaService;
use App\Services\Ai\AiTokenWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class AiUsageController extends Controller
{
    public function __construct(
        private readonly AiApiKeyService $apiKeyService,
        private readonly AiQuotaService $quotaService,
        private readonly AiTokenWalletService $walletService,
    ) {}

    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user() ?? Auth::user();

        // 1. AI Wallet Summary (Available, Used this month, Expiring Soon, Breakdown, Warnings)
        $walletSummary = $this->walletService->getWalletSummary($customer);

        // 2. Customer's Active Licenses
        $licenses = License::with(['product', 'subscriptionPlan.aiPlanConfig'])
            ->where('customer_id', $customer->getKey())
            ->where('status', License::STATUS_ACTIVE)
            ->get();

        $licenseIds = $licenses->pluck('id')->toArray();

        // 3. API Keys
        $keys = AiApiKey::with('license.product')
            ->where('customer_id', $customer->getKey())
            ->latest()
            ->get();

        $apiKeyIds = $keys->pluck('id')->toArray();

        // 4. Token Lots History (Top Up, Subscription, Bonus, Promo)
        $tokenLots = AiTokenLot::where('customer_id', $customer->getKey())
            ->orderByRaw("FIELD(status, 'active', 'depleted', 'expired', 'cancelled') ASC")
            ->orderBy('expires_at', 'asc')
            ->paginate(10, ['*'], 'lots_page');

        // 5. Token Transaction Ledger History
        $transactions = AiTokenTransaction::with('tokenLot')
            ->where('customer_id', $customer->getKey())
            ->latest('created_at')
            ->paginate(15, ['*'], 'tx_page');

        // 6. Recent Usage Logs for this customer
        $recentLogs = AiUsageLog::with(['apiKey', 'tokenLot'])
            ->where(function ($query) use ($customer, $licenseIds, $apiKeyIds) {
                $query->where('customer_id', $customer->getKey())
                      ->orWhereIn('license_id', $licenseIds)
                      ->orWhereIn('ai_api_key_id', $apiKeyIds);
            })
            ->latest('created_at')
            ->paginate(20, ['*'], 'logs_page');

        // 7. Multi-Model Usage Breakdown
        $modelBreakdown = AiUsageLog::where(function ($query) use ($customer, $licenseIds, $apiKeyIds) {
                $query->where('customer_id', $customer->getKey())
                      ->orWhereIn('license_id', $licenseIds)
                      ->orWhereIn('ai_api_key_id', $apiKeyIds);
            })
            ->where('created_at', '>=', now()->startOfMonth())
            ->select('model', 'provider', DB::raw('SUM(total_tokens) as total_tokens'), DB::raw('COUNT(*) as total_requests'))
            ->groupBy('model', 'provider')
            ->orderByDesc('total_tokens')
            ->get();

        // 8. AI Token Packages Available for Top Up
        $tokenPackages = AiTokenPackage::active()->get();

        return view('customer.ai.usage', compact(
            'customer',
            'walletSummary',
            'licenses',
            'keys',
            'tokenLots',
            'transactions',
            'recentLogs',
            'modelBreakdown',
            'tokenPackages'
        ));
    }

    public function purchasePackage(Request $request)
    {
        $customer = Auth::guard('customer')->user() ?? Auth::user();

        $validated = $request->validate([
            'license_id' => 'nullable|uuid|exists:licenses,id',
            'package_id' => 'required|uuid|exists:ai_token_packages,id',
        ]);

        $license = null;
        if (!empty($validated['license_id'])) {
            $license = License::where('id', $validated['license_id'])
                ->where('customer_id', $customer->getKey())
                ->where('status', License::STATUS_ACTIVE)
                ->first();
        }

        $package = AiTokenPackage::where('id', $validated['package_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $invoice = DB::transaction(function () use ($customer, $license, $package) {
            $invoiceNumber = 'INV-AI-' . strtoupper(date('Ymd')) . '-' . strtoupper(Str::random(6));
            $subtotal = (float) $package->price;
            $taxAmount = round($subtotal * 0.11, 2);
            $netAmount = round($subtotal + $taxAmount, 2);

            $transaction = \App\Models\Transaction::create([
                'customer_id'      => $customer->getKey(),
                'subscription_id'  => $license?->subscription_id,
                'type'             => 'ai_token_topup',
                'description'      => "Top-Up AI Token: {$package->name} (+" . number_format($package->token_amount) . " Token, Berlaku 30 Hari)",
                'invoice_number'   => $invoiceNumber,
                'gross_amount'     => $package->price,
                'voucher_discount' => 0,
                'subtotal_amount'  => $subtotal,
                'tax_amount'       => $taxAmount,
                'net_amount'       => $netAmount,
                'payment_method'   => 'pending',
                'payment_gateway'  => 'midtrans',
                'status'           => 'pending',
            ]);

            $invoice = \App\Models\Invoice::create([
                'transaction_id'  => $transaction->id,
                'invoice_number'  => $invoiceNumber,
                'customer_id'     => $customer->getKey(),
                'subtotal_amount' => $subtotal,
                'tax_amount'      => $taxAmount,
                'amount'          => $netAmount,
                'status'          => 'issued',
                'issued_at'       => now(),
                'due_at'          => now()->addDays(3),
            ]);

            \App\Models\AiTokenPurchase::create([
                'customer_id'         => $customer->getKey(),
                'license_id'          => $license?->id,
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
