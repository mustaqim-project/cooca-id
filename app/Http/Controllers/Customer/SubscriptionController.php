<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Actions\Subscription\CreateSubscription\CreateSubscriptionAction;
use App\DTOs\Subscription\SubscriptionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateSubscriptionRequest;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use App\Services\Subscription\SubscriptionService;
use App\Services\Voucher\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly CreateSubscriptionAction $createSubscriptionAction,
        private readonly PaymentService $paymentService,
        private readonly VoucherService $voucherService,
    ) {}

    public function index()
    {
        $subscriptions = Subscription::where('customer_id', Auth::id())
            ->with(['subscriptionPlan.product', 'license'])
            ->paginate(15);

        return view('customer.subscriptions.index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function create(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $customer = auth('customer')->user();
        $showCompanyFields = !$customer->isCompanyProfileComplete();
        $companyProfile = $customer->companyProfile ?? new \App\Models\CompanyProfile();

        return view('customer.subscriptions.create', [
            'preselectedProductId' => $request->query('product_id'),
            'preselectedPlanId' => $request->query('plan_id'),
            'showCompanyFields' => $showCompanyFields,
            'companyProfile' => $companyProfile,
            'customer' => $customer,
        ]);
    }

    public function checkDomain(Request $request): \Illuminate\Http\JsonResponse
    {
        $domain = $request->input('domain');
        if (!$domain) {
            return response()->json(['available' => false, 'message' => 'Domain/Subdomain is required.']);
        }

        $domainStr = str_contains($domain, '.') ? $domain : $domain . '.cooca.id';

        if (!preg_match('/^[a-zA-Z0-9.-]+$/', $domainStr)) {
            return response()->json(['available' => false, 'message' => 'Hanya huruf, angka, dan strip yang diperbolehkan.']);
        }

        $existsInLicenses = \App\Models\License::where('domain', $domainStr)
            ->where('customer_id', '!=', auth('customer')->id())
            ->exists();

        $subdomainOnly = str_replace('.cooca.id', '', $domainStr);
        $existsInRequests = \App\Models\ErpRequest::where('requested_subdomain', $subdomainOnly)
            ->where('customer_id', '!=', auth('customer')->id())
            ->whereNotIn('status', [\App\Models\ErpRequest::STATUS_REJECTED, \App\Models\ErpRequest::STATUS_TRIAL_EXPIRED])
            ->exists();

        $exists = $existsInLicenses || $existsInRequests;

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Domain Tidak Tersedia' : 'Domain tersedia.'
        ]);
    }

    public function show(string $subscription)
    {
        $subscription = Subscription::where('id', $subscription)
            ->where('customer_id', Auth::id())
            ->with(['subscriptionPlan.product', 'license'])
            ->first();

        if (!$subscription) {
            abort(404);
        }

        return view('customer.subscriptions.show', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * Store a newly created subscription.
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $customer = Auth::user();
        if (!$customer->isCompanyProfileComplete()) {
            \App\Models\CompanyProfile::updateOrCreate(
                ['customer_id' => $customer->id],
                $request->only([
                    'company_name',
                    'industry',
                    'company_size',
                    'phone',
                    'address',
                    'city',
                    'province',
                    'postal_code',
                    'npwp',
                    'website',
                ])
            );
            $customer->load('companyProfile');
        }

        $data     = $request->validated();

        $product = \App\Models\Product::where('slug', $data['product_slug'])->firstOrFail();

        $domainStr = $data['domain'];
        if (!str_contains($domainStr, '.')) {
            $domainStr .= '.cooca.id';
        }

        $domain = \App\Models\Domain::firstOrCreate(
            ['domain' => $domainStr],
            [
                'customer_id' => $customer->getKey(),
                'type'        => str_contains($domainStr, 'cooca.id')
                    ? \App\Models\Domain::TYPE_SUBDOMAIN
                    : \App\Models\Domain::TYPE_CUSTOM_DOMAIN,
                'status'      => \App\Models\Domain::STATUS_PENDING,
            ]
        );

        $license = \App\Models\License::where('customer_id', $customer->getKey())
            ->where('domain_id', $domain->id)
            ->where('product_id', $product->id)
            ->first();

        // Prevent creating a license for a domain that is already taken by another customer
        $domainTaken = \App\Models\License::where('domain', $domain->domain)
            ->where('customer_id', '!=', $customer->getKey())
            ->exists();

        if ($domainTaken) {
            return back()->with('error', 'Domain sudah digunakan oleh pelanggan lain. Silakan pilih domain lain.');
        }

        if (!$license) {
            do {
                $licenseCode = strtoupper(Str::random(16));
            } while (\App\Models\License::where('license_code', $licenseCode)->exists());

            do {
                $tokenCode = strtoupper(Str::random(16));
            } while (\App\Models\License::where('token_code', $tokenCode)->exists());

            $license = \App\Models\License::create([
                'customer_id'          => $customer->getKey(),
                'product_id'           => $product->id,
                'subscription_plan_id' => $data['subscription_plan_id'],
                'domain_id'            => $domain->id,
                'license_code'         => $licenseCode,
                'token_code'           => $tokenCode,
                'domain'               => $domain->domain,
                'status'               => \App\Models\License::STATUS_INACTIVE,
                'is_trial'             => false,
            ]);
        }

        $subscriptionData = new SubscriptionData(
            customerId: \Ramsey\Uuid\Uuid::fromString((string) $customer->getKey()),
            licenseId: \Ramsey\Uuid\Uuid::fromString((string) $license->id),
            subscriptionPlanId: \Ramsey\Uuid\Uuid::fromString((string) $data['subscription_plan_id']),
            startedAt: isset($data['started_at']) ? new \DateTimeImmutable($data['started_at']) : new \DateTimeImmutable(),
            expiresAt: isset($data['expires_at']) ? new \DateTimeImmutable($data['expires_at']) : null,
        );

        $subscription = $this->createSubscriptionAction->execute($subscriptionData);

        $license->update([
            'subscription_id' => $subscription->id,
            'is_trial' => false,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Subscription created successfully',
                'data'    => $subscription,
            ], 201);
        }

        return redirect()->route('customer.subscriptions.checkout', $subscription->id)
            ->with('success', 'Berhasil memilih paket langganan. Silakan selesaikan pembayaran.');
    }

    public function cancel(Request $request, string $id)
    {
        $customer     = Auth::user();
        $subscription = Subscription::where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->first();

        if (!$subscription) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Subscription not found'], 404);
            }
            return redirect()->route('customer.subscriptions.index')
                ->with('error', 'Subscription not found');
        }

        // If unpaid (trial), delete completely (cancel unpaid subscription)
        if ($subscription->status === 'trial') {
            DB::beginTransaction();
            try {
                // Delete associated license if it exists and is inactive (unpaid)
                if ($subscription->license && $subscription->license->status === 'inactive') {
                    // Delete associated domain if it exists
                    if ($subscription->license->domainRecord) {
                        $subscription->license->domainRecord->delete();
                    }
                    $subscription->license->delete();
                }

                // Get transaction IDs associated with this subscription
                $transactionIds = Transaction::where('subscription_id', $subscription->id)->pluck('id');

                // Delete pending invoices associated with those transactions
                Invoice::whereIn('transaction_id', $transactionIds)
                    ->whereIn('status', ['issued', 'pending'])
                    ->delete();

                // Delete pending transactions
                Transaction::where('subscription_id', $subscription->id)
                    ->whereIn('status', ['pending', 'expire', 'cancel'])
                    ->delete();

                // Delete the subscription
                $subscription->delete();

                DB::commit();

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Subscription deleted successfully']);
                }
                return redirect()->route('customer.products.index')
                    ->with('success', 'Langganan berhasil dibatalkan.');
            } catch (\Exception $e) {
                DB::rollBack();
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Gagal membatalkan langganan: ' . $e->getMessage()], 500);
                }
                return back()->with('error', 'Gagal membatalkan langganan: ' . $e->getMessage());
            }
        }

        $this->subscriptionService->cancelSubscription($subscription);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Subscription cancelled successfully']);
        }

        return redirect()->route('customer.subscriptions.index')
            ->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Redirect to the checkout page — payment is required to renew.
     */
    public function renew(Request $request, string $id)
    {
        $subscription = Subscription::where('id', $id)
            ->where('customer_id', Auth::id())
            ->first();

        if (!$subscription) {
            return redirect()->route('customer.subscriptions.index')
                ->with('error', 'Subscription not found');
        }

        return redirect()->route('customer.subscriptions.checkout', $subscription->id);
    }

    /**
     * Show the renewal checkout page with pricing summary.
     */
    public function checkout(string $id)
    {
        $subscription = Subscription::where('id', $id)
            ->where('customer_id', Auth::id())
            ->with(['subscriptionPlan.product', 'license'])
            ->firstOrFail();

        $plan            = $subscription->subscriptionPlan;
        $price           = (float) ($plan?->price ?? 0);
        $discountPercent = (float) ($plan?->discount_percent ?? 0);
        $discountAmount  = round($price * ($discountPercent / 100), 2);
        $netAmount       = round($price - $discountAmount, 2);

        // Show warning if there's already a pending unpaid renewal transaction
        $pendingTransaction = Transaction::where('subscription_id', $subscription->id)
            ->where('type', 'renewal')
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('customer.subscriptions.checkout', compact(
            'subscription',
            'plan',
            'price',
            'discountPercent',
            'discountAmount',
            'netAmount',
            'pendingTransaction',
        ));
    }

    /**
     * Validate and apply voucher via AJAX
     */
    public function applyVoucher(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        $customer = Auth::user();
        $subscription = Subscription::where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->with(['subscriptionPlan'])
            ->first();

        if (!$subscription || !$subscription->subscriptionPlan) {
            return response()->json(['success' => false, 'message' => 'Langganan tidak valid.'], 400);
        }

        $price = (float) $subscription->subscriptionPlan->price;
        $discountPercent = (float) $subscription->subscriptionPlan->discount_percent;
        $planDiscountAmount = round($price * ($discountPercent / 100), 2);
        $basePurchaseAmount = $price - $planDiscountAmount; // Apply voucher to the discounted plan price

        try {
            $productIds = [$subscription->subscriptionPlan->product_id];
            $voucherData = $this->voucherService->applyVoucher($request->voucher_code, $basePurchaseAmount, $customer, $productIds);

            if (!$voucherData) {
                return response()->json(['success' => false, 'message' => 'Voucher tidak valid atau sudah kedaluwarsa.'], 400);
            }

            $voucherDiscount = $this->voucherService->calculateDiscount($voucherData, $basePurchaseAmount);
            $newTotal = max(0, $basePurchaseAmount - $voucherDiscount);

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil diterapkan.',
                'discount' => $voucherDiscount,
                'new_total' => $newTotal,
                'voucher_code' => $voucherData->code,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Create a pending Transaction + Invoice and open the Midtrans Snap payment popup.
     */
    public function processCheckout(Request $request, string $id)
    {
        $customer     = Auth::user();
        $subscription = Subscription::where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->with(['subscriptionPlan'])
            ->firstOrFail();

        $plan  = $subscription->subscriptionPlan;
        $price = (float) ($plan?->price ?? 0);

        if ($price <= 0) {
            return back()->with('error', 'Tidak ada harga yang tersedia untuk plan ini.');
        }

        $discountPercent = (float) ($plan?->discount_percent ?? 0);
        $planDiscountAmount = round($price * ($discountPercent / 100), 2);

        $baseAmount = round($price - $planDiscountAmount, 2);
        $voucherDiscountAmount = 0;
        $voucherId = null;

        // Validasi Voucher jika dikirim
        if ($request->filled('voucher_code')) {
            try {
                $productIds = [$plan->product_id];
                $voucherData = $this->voucherService->applyVoucher($request->voucher_code, $baseAmount, $customer, $productIds);
                if ($voucherData) {
                    $voucherDiscountAmount = $this->voucherService->calculateDiscount($voucherData, $baseAmount);
                    // Fetch the original voucher to get its ID
                    $voucher = \App\Models\Voucher::where('code', $voucherData->code)->first();
                    if ($voucher) {
                        $voucherId = $voucher->id;
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        $totalDiscount = $planDiscountAmount + $voucherDiscountAmount;
        $netAmount = max(0, round($price - $totalDiscount, 2));

        try {
            $snapData = DB::transaction(function () use ($customer, $subscription, $price, $totalDiscount, $netAmount, $voucherId) {
                // Generate unique invoice number
                $yearMonth     = now()->format('Ym');
                $lastTxn       = Transaction::where('invoice_number', 'like', "INV/{$yearMonth}%")
                    ->orderBy('invoice_number', 'desc')
                    ->lockForUpdate()
                    ->first();
                $lastNum       = $lastTxn ? (int) substr($lastTxn->invoice_number, -5) : 0;
                $invoiceNumber = "INV/{$yearMonth}/" . str_pad((string) ($lastNum + 1), 5, '0', STR_PAD_LEFT);

                // Create pending transaction tagged as 'renewal'
                $transaction = Transaction::create([
                    'customer_id'      => $customer->getKey(),
                    'subscription_id'  => $subscription->id,
                    'type'             => 'renewal',
                    'invoice_number'   => $invoiceNumber,
                    'gross_amount'     => $price,
                    'voucher_discount' => $totalDiscount,
                    'voucher_id'       => $voucherId,
                    'net_amount'       => $netAmount,
                    'payment_method'   => 'midtrans',
                    'payment_gateway'  => 'midtrans',
                    'status'           => 'pending',
                ]);

                // Create corresponding pending invoice
                Invoice::create([
                    'transaction_id' => $transaction->id,
                    'invoice_number' => $invoiceNumber,
                    'customer_id'    => $customer->getKey(),
                    'amount'         => $netAmount,
                    'status'         => 'issued',
                    'issued_at'      => now(),
                    'due_at'         => now()->addDays(3),
                ]);

                return $this->paymentService->createSnapTransaction($transaction);
            });

            $subscription->load('subscriptionPlan.product', 'license');
            $plan = $subscription->subscriptionPlan;

            return view('customer.subscriptions.checkout', [
                'subscription'       => $subscription,
                'plan'               => $plan,
                'price'              => $price,
                'discountPercent'    => $discountPercent,
                'discountAmount'     => $discountAmount,
                'netAmount'          => $netAmount,
                'pendingTransaction' => null,
                'snapToken'          => $snapData['snap_token'] ?? null,
                'snapUrl'            => $snapData['snap_url'] ?? null,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
}
