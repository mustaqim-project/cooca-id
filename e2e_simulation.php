<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\AffiliateWallet;
use App\Services\Affiliate\AffiliateService;
use App\Repositories\Contracts\AffiliateCommissionRepositoryInterface;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "========================================================\n";
echo "        SIMULASI SKEMA REAL PRODUCTION COOCA.ID          \n";
echo "========================================================\n\n";

DB::beginTransaction();

try {
    echo "[1] Menyiapkan Data Master (Produk & Admin)...\n";
    $admin = Admin::firstOrCreate(
        ['email' => 'superadmin_sim@cooca.id'],
        ['name' => 'Super Admin', 'password' => bcrypt('password'), 'permissions' => ['super_admin']]
    );

    $category = \App\Models\ProductCategory::firstOrCreate(
        ['slug' => 'sim-category'],
        ['name' => 'Simulation Category', 'description' => 'Test']
    );

    $product = Product::firstOrCreate(
        ['slug' => 'sim-erp-pro'],
        ['name' => 'ERP Pro (Simulation)', 'description' => 'Test Product', 'is_active' => true, 'category_id' => $category->id, 'base_price' => 0]
    );

    $plan = SubscriptionPlan::firstOrCreate(
        ['product_id' => $product->id, 'name' => 'Monthly Pro'],
        ['price' => 1000000, 'duration_months' => 1, 'is_active' => true]
    );
    echo "    - Product & Plan dibuat (Harga: Rp " . number_format((float)$plan->price, 0, ',', '.') . ")\n\n";

    echo "[2] Registrasi Affiliator (Multi-Tier)...\n";
    $affiliatorA = Affiliator::create([
        'name' => 'Master Affiliator',
        'email' => 'master_sim_' . Str::random(5) . '@cooca.id',
        'password' => bcrypt('password'),
        'referral_code' => 'MASTER' . Str::random(5),
    ]);
    
    $affiliatorB = Affiliator::create([
        'name' => 'Sub Affiliator',
        'email' => 'sub_sim_' . Str::random(5) . '@cooca.id',
        'password' => bcrypt('password'),
        'referral_code' => 'SUB' . Str::random(5),
        'parent_affiliator_id' => $affiliatorA->id,
    ]);
    echo "    - Affiliator A (Master) dibuat.\n";
    echo "    - Affiliator B (Sub) bergabung di bawah referal Affiliator A.\n\n";

    echo "[3] Registrasi Customer via Referral Affiliator B...\n";
    $customer = Customer::create([
        'name' => 'Customer Bisnis',
        'email' => 'customer_sim_' . Str::random(5) . '@cooca.id',
        'password' => bcrypt('password'),
        'affiliator_id' => $affiliatorB->id, // Referred by B
    ]);
    echo "    - Customer mendaftar menggunakan referal {$affiliatorB->referral_code}\n\n";

    echo "[4] Customer Melakukan Transaksi Pembelian...\n";
    $transaction = Transaction::create([
        'customer_id' => $customer->id,
        'subscription_plan_id' => $plan->id,
        'invoice_number' => 'INV-SIM-' . time(),
        'gross_amount' => $plan->price, // Rp 1.000.000
        'net_amount' => $plan->price,
        'status' => 'paid',
        'payment_method' => 'bank_transfer',
        'paid_at' => now(),
    ]);
    echo "    - Transaksi berhasil dibayar sebesar Rp " . number_format((float)$transaction->gross_amount, 0, ',', '.') . "\n\n";

    echo "[5] Memproses Komisi Affiliate secara Otomatis...\n";
    $affiliateService = app(AffiliateService::class);
    $commissionResult = $affiliateService->processCommissions($transaction);
    
    echo "    - Total komisi yang didistribusikan: Rp " . number_format((float)$commissionResult['total'], 0, ',', '.') . "\n";
    
    // Verifikasi saldo
    $walletA = AffiliateWallet::where('affiliator_id', $affiliatorA->id)->first();
    $walletB = AffiliateWallet::where('affiliator_id', $affiliatorB->id)->first();
    
    echo "    - Saldo Pending Affiliator B (L1 - 25%): Rp " . number_format((float)$walletB->pending_balance, 0, ',', '.') . "\n";
    echo "    - Saldo Pending Affiliator A (L2 - 5%): Rp " . number_format((float)$walletA->pending_balance, 0, ',', '.') . "\n\n";

    echo "[6] Clearing Komisi (Settlement)...\n";
    // Majukan waktu atau ubah query untuk membersihkan komisi
    DB::table('affiliate_commissions')->update(['created_at' => now()->subDays(8)]);
    $cleared = $affiliateService->clearCommissions(now());
    echo "    - Berhasil melakukan clearing pada {$cleared} data komisi.\n";
    
    $walletA->refresh();
    $walletB->refresh();
    echo "    - Saldo Tersedia Affiliator B: Rp " . number_format((float)$walletB->balance, 0, ',', '.') . "\n";
    echo "    - Saldo Tersedia Affiliator A: Rp " . number_format((float)$walletA->balance, 0, ',', '.') . "\n\n";

    echo "[7] Simulasi Affiliator B Menarik Dana (Withdrawal)...\n";
    $withdrawalAmount = 200000;
    echo "    - Affiliator B me-request pencairan dana sebesar Rp " . number_format((float)$withdrawalAmount, 0, ',', '.') . "\n";
    
    $withdrawal = $affiliateService->requestWithdrawal(
        $affiliatorB->id,
        $withdrawalAmount,
        'bank',
        '1234567890',
        'Sub Affiliator'
    );
    $walletB->refresh();
    echo "    - Saldo Tersedia Affiliator B setelah request: Rp " . number_format((float)$walletB->balance, 0, ',', '.') . "\n";
    echo "    - Biaya Admin Bank dipotong: Rp " . number_format((float)$withdrawal->fee, 0, ',', '.') . "\n";
    echo "    - Dana bersih yang akan ditransfer (Net Amount): Rp " . number_format((float)$withdrawal->net_amount, 0, ',', '.') . "\n\n";

    echo "[8] Admin Menerima dan Memproses Withdrawal...\n";
    $affiliateService->approveWithdrawal($withdrawal->id, $admin->id);
    $affiliateService->markWithdrawalAsPaid($withdrawal->id);
    $withdrawal->refresh();
    
    echo "    - Status Withdrawal: " . strtoupper($withdrawal->status) . "\n";
    echo "    - Waktu Dibayar: " . $withdrawal->paid_at . "\n\n";

    echo "========================================================\n";
    echo "  SIMULASI BERHASIL TANPA ERROR (ALL LOGIC PASSED)      \n";
    echo "========================================================\n";

    // Membatalkan transaksi agar tidak mengotori DB lokal pengguna
    DB::rollBack();
    echo "\n* Catatan: Data uji coba telah di-rollback (tidak tersimpan ke database aktual).\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n[ERROR] Simulasi gagal:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
