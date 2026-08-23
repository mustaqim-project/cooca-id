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
use Illuminate\Support\Facades\Gate;

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
        $domain = trim((string) $request->input('domain'));
        if (!$domain) {
            return response()->json(['available' => false, 'message' => 'Domain/Subdomain is required.']);
        }

        $domainStr = str_contains($domain, '.') ? $domain : $domain . '.cooca.id';

        if (!preg_match('/^[a-zA-Z0-9.-]+$/', $domainStr)) {
            return response()->json(['available' => false, 'message' => 'Hanya huruf, angka, dan strip yang diperbolehkan.']);
        }

        // 1. Cek apakah domain terdaftar pada Lisensi yang aktif / valid
        $activeLicense = \App\Models\License::where('domain', $domainStr)
            ->whereIn('status', [\App\Models\License::STATUS_ACTIVE, \App\Models\License::STATUS_SUSPENDED])
            ->first();

        if ($activeLicense) {
            return response()->json([
                'available' => false,
                'message' => 'Domain ini sudah aktif digunakan oleh lisensi lain. Silakan pilih nama domain/subdomain berbeda.'
            ]);
        }

        // 2. Cek apakah domain ada di ERP Request yang sedang aktif
        $subdomainOnly = str_replace('.cooca.id', '', $domainStr);
        $activeRequest = \App\Models\ErpRequest::where('requested_subdomain', $subdomainOnly)
            ->whereNotIn('status', [\App\Models\ErpRequest::STATUS_REJECTED, \App\Models\ErpRequest::STATUS_TRIAL_EXPIRED])
            ->first();

        if ($activeRequest) {
            return response()->json([
                'available' => false,
                'message' => 'Subdomain ini sedang aktif dalam proses uji coba / setup ERP.'
            ]);
        }

        // 3. Cek apakah domain ini sedang dalam proses pembayaran pending yang belum expired (< 1 jam)
        $pendingLicense = \App\Models\License::where('domain', $domainStr)
            ->where('status', \App\Models\License::STATUS_INACTIVE)
            ->whereHas('subscription.transactions', function ($query) {
                $query->where('status', 'pending')
                    ->where('created_at', '>=', now()->subHours(1));
            })
            ->with(['subscription.transactions' => function ($query) {
                $query->where('status', 'pending')
                    ->where('created_at', '>=', now()->subHours(1))
                    ->latest();
            }])
            ->first();

        if ($pendingLicense) {
            $pendingTx = $pendingLicense->subscription?->transactions?->first();
            $isMine = ($pendingLicense->customer_id === auth('customer')->id());

            if ($isMine) {
                return response()->json([
                    'available' => false,
                    'is_pending_mine' => true,
                    'transaction_id' => $pendingTx?->id,
                    'message' => 'Domain ini sudah Anda pesan dan sedang menunggu pembayaran (Batas waktu 1 jam). Silakan lanjutkan pembayaran pesanan sebelumnya.'
                ]);
            }

            return response()->json([
                'available' => false,
                'message' => 'Domain ini sedang dalam proses pemesanan oleh pelanggan lain (Menunggu pembayaran).'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Domain/Subdomain tersedia untuk dipesan.'
        ]);
    }

    public function checkHostingerDomain(Request $request, \App\Services\Hostinger\HostingerDomainService $hostingerService): JsonResponse
    {
        $domain = (string) $request->input('domain', '');
        if (empty($domain)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nama domain yang ingin dicari.',
                'results' => [],
            ], 422);
        }

        $data = $hostingerService->checkDomainWithPrices($domain);
        return response()->json($data);
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

        Gate::authorize('view', $subscription);

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

        $data = $request->validated();
        $product = \App\Models\Product::where('slug', $data['product_slug'])->firstOrFail();

        $domainStr = trim($data['domain']);
        if (!str_contains($domainStr, '.')) {
            $domainStr .= '.cooca.id';
        }

        // 1. Validasi Domain: Tidak boleh duplicate dengan lisensi yang sudah aktif/suspended
        $activeLicenseExists = \App\Models\License::where('domain', $domainStr)
            ->whereIn('status', [\App\Models\License::STATUS_ACTIVE, \App\Models\License::STATUS_SUSPENDED])
            ->exists();

        if ($activeLicenseExists) {
            return back()->withInput()->with('error', "Domain '{$domainStr}' sudah aktif digunakan. Silakan pilih domain atau subdomain lain.");
        }

        // 2. Validasi Domain: Tidak boleh duplicate dengan ERP Request yang sedang aktif
        $subdomainOnly = str_replace('.cooca.id', '', $domainStr);
        $requestExists = \App\Models\ErpRequest::where('requested_subdomain', $subdomainOnly)
            ->whereNotIn('status', [\App\Models\ErpRequest::STATUS_REJECTED, \App\Models\ErpRequest::STATUS_TRIAL_EXPIRED])
            ->exists();

        if ($requestExists) {
            return back()->withInput()->with('error', "Subdomain '{$subdomainOnly}' sedang aktif dalam proses uji coba / setup ERP. Silakan gunakan subdomain lain.");
        }

        // 3. Validasi Pending Payment: Tidak boleh duplicate pending payment dengan domain yang sama (< 1 jam)
        $pendingLicense = \App\Models\License::where('domain', $domainStr)
            ->where('status', \App\Models\License::STATUS_INACTIVE)
            ->whereHas('subscription.transactions', function ($query) {
                $query->where('status', 'pending')
                    ->where('created_at', '>=', now()->subHours(1));
            })
            ->first();

        if ($pendingLicense) {
            if ($pendingLicense->customer_id === $customer->getKey()) {
                $pendingTx = $pendingLicense->subscription?->transactions()->where('status', 'pending')->latest()->first();
                if ($pendingTx) {
                    return redirect()->route('customer.payments.show', $pendingTx->id)
                        ->with('error', "Anda sudah memiliki pesanan yang menunggu pembayaran untuk domain '{$domainStr}'. Silakan selesaikan pembayaran tagihan yang ada.");
                }
            }
            return back()->withInput()->with('error', "Domain '{$domainStr}' sedang dalam proses pembayaran oleh pengguna lain (Batas waktu 1 jam).");
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

        Gate::authorize('update', $subscription);

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

        Gate::authorize('update', $subscription);

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

        Gate::authorize('view', $subscription);

        $plan            = $subscription->subscriptionPlan;
        $price           = (float) ($plan?->price ?? 0);
        $discountPercent = (float) ($plan?->discount_percent ?? 0);
        $discountAmount  = round($price * ($discountPercent / 100), 2);
        $subtotal        = round($price - $discountAmount, 2);
        $taxAmount       = round($subtotal * 0.11, 2);
        $netAmount       = round($subtotal + $taxAmount, 2);

        // Show warning if there's already a pending unpaid renewal transaction
        $pendingTransaction = Transaction::where('subscription_id', $subscription->id)
            ->where('type', 'renewal')
            ->where('status', 'pending')
            ->latest()
            ->first();

        $bankSettings = [
            'active' => (bool) \App\Models\Setting::get('payment.bank_transfer.active', true),
            'bank_name' => \App\Models\Setting::get('payment.bank_transfer.bank_name', 'Bank Central Asia (BCA)'),
            'account_number' => \App\Models\Setting::get('payment.bank_transfer.account_number', '8830-8899-8800'),
            'account_name' => \App\Models\Setting::get('payment.bank_transfer.account_name', 'PT COOCA TECHNOLOGIES INDONESIA'),
            'instructions' => \App\Models\Setting::get('payment.bank_transfer.instructions', 'Silakan transfer sesuai jumlah total tagihan hingga digit terakhir. Setelah transfer, wajib unggah bukti transfer/struk pada form di bawah ini agar tim kami dapat memverifikasi pembayaran Anda.'),
        ];

        return view('customer.subscriptions.checkout', compact(
            'subscription',
            'plan',
            'price',
            'discountPercent',
            'discountAmount',
            'subtotal',
            'taxAmount',
            'netAmount',
            'pendingTransaction',
            'bankSettings',
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
            $subtotal = max(0, round($basePurchaseAmount - $voucherDiscount, 2));
            $taxAmount = round($subtotal * 0.11, 2);
            $newTotal = round($subtotal + $taxAmount, 2);

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil diterapkan.',
                'discount' => $voucherDiscount,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'new_total' => $newTotal,
                'voucher_code' => $voucherData->code,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Create a pending Transaction + Invoice and process either Midtrans Snap or Manual Transfer with proof.
     */
    public function processCheckout(Request $request, string $id)
    {
        $customer     = Auth::user();
        $subscription = Subscription::where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->with(['subscriptionPlan'])
            ->firstOrFail();

        Gate::authorize('update', $subscription);

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
        $subtotal = max(0, round($price - $totalDiscount, 2));
        $taxAmount = round($subtotal * 0.11, 2);
        $netAmount = round($subtotal + $taxAmount, 2);

        $paymentType = $request->input('payment_type', 'midtrans');

        // TIPE 2: MANUAL BANK TRANSFER
        if (in_array($paymentType, ['manual_transfer', 'bank_transfer_manual', 'manual'])) {
            $request->validate([
                'payment_proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'sender_name' => 'required|string|max:255',
                'payment_notes' => 'nullable|string|max:1000',
            ], [
                'payment_proof.required' => 'Wajib mengunggah file bukti transfer.',
                'payment_proof.mimes' => 'Format file bukti bayar harus JPG, PNG, atau PDF.',
                'sender_name.required' => 'Wajib mengisi nama pemilik rekening pengirim.',
            ]);

            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            try {
                $invoice = DB::transaction(function () use ($customer, $subscription, $price, $totalDiscount, $subtotal, $taxAmount, $netAmount, $voucherId, $proofPath, $request) {
                    $yearMonth     = now()->format('Ym');
                    $lastTxn       = Transaction::where('invoice_number', 'like', "INV/{$yearMonth}%")
                        ->orderBy('invoice_number', 'desc')
                        ->lockForUpdate()
                        ->first();
                    $lastNum       = $lastTxn ? (int) substr($lastTxn->invoice_number, -5) : 0;
                    $invoiceNumber = "INV/{$yearMonth}/" . str_pad((string) ($lastNum + 1), 5, '0', STR_PAD_LEFT);

                    $transaction = Transaction::create([
                        'customer_id'               => $customer->getKey(),
                        'subscription_id'           => $subscription->id,
                        'type'                      => 'renewal',
                        'invoice_number'            => $invoiceNumber,
                        'gross_amount'              => $price,
                        'voucher_discount'          => $totalDiscount,
                        'subtotal_amount'           => $subtotal,
                        'tax_amount'                => $taxAmount,
                        'voucher_id'                => $voucherId,
                        'net_amount'                => $netAmount,
                        'payment_method'            => 'bank_transfer_manual',
                        'payment_gateway'           => 'manual',
                        'payment_proof'             => $proofPath,
                        'payment_proof_uploaded_at' => now(),
                        'sender_name'               => $request->input('sender_name'),
                        'payment_notes'             => $request->input('payment_notes'),
                        'status'                    => 'pending',
                    ]);

                    $inv = Invoice::create([
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

                    // Send email notification to agungmustaqim15@gmail.com and cooca.idn@gmail.com
                    try {
                        \Illuminate\Support\Facades\Mail::to(['agungmustaqim15@gmail.com', 'cooca.idn@gmail.com'])
                            ->send(new \App\Mail\Admin\SubscriptionPaymentReceivedMail($transaction, 'Bukti Transfer Baru Diunggah (Menunggu Verifikasi)'));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('[SubscriptionController] Failed to send admin payment proof email: ' . $e->getMessage());
                    }

                    return $inv;
                });

                return redirect()->route('customer.invoices.show', $invoice->id)
                    ->with('success', 'Bukti pembayaran berhasil diunggah! Pembayaran Anda sedang menunggu verifikasi oleh tim kami.');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
            }
        }

        // TIPE 1: MIDTRANS GATEWAY
        try {
            $snapData = DB::transaction(function () use ($customer, $subscription, $price, $totalDiscount, $subtotal, $taxAmount, $netAmount, $voucherId) {
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
                    'subtotal_amount'  => $subtotal,
                    'tax_amount'       => $taxAmount,
                    'voucher_id'       => $voucherId,
                    'net_amount'       => $netAmount,
                    'payment_method'   => 'midtrans',
                    'payment_gateway'  => 'midtrans',
                    'status'           => 'pending',
                ]);

                // Create corresponding pending invoice
                Invoice::create([
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

                return $this->paymentService->createSnapTransaction($transaction);
            });

            $subscription->load('subscriptionPlan.product', 'license');
            $plan = $subscription->subscriptionPlan;

            $bankSettings = [
                'active' => (bool) \App\Models\Setting::get('payment.bank_transfer.active', true),
                'bank_name' => \App\Models\Setting::get('payment.bank_transfer.bank_name', 'Bank Central Asia (BCA)'),
                'account_number' => \App\Models\Setting::get('payment.bank_transfer.account_number', '8830-8899-8800'),
                'account_name' => \App\Models\Setting::get('payment.bank_transfer.account_name', 'PT COOCA TECHNOLOGIES INDONESIA'),
                'instructions' => \App\Models\Setting::get('payment.bank_transfer.instructions', 'Silakan transfer sesuai jumlah total tagihan.'),
            ];

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
                'bankSettings'       => $bankSettings,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
}
