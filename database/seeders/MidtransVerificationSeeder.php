<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MidtransVerificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create/Update Verifier Account
        $customer = Customer::updateOrCreate(
            ['email' => 'midtrans.verification@cooca.id'],
            [
                'name'              => 'Midtrans Verifikator',
                'phone'             => '6288899992222',
                'password'          => Hash::make('Midtrans2026!'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'business_name'     => 'Midtrans Verifikator',
            ]
        );

        // 2. Find or Create Product "COOCA POS Pro"
        $product = Product::firstOrCreate(
            ['name' => 'COOCA POS Pro'],
            [
                'slug'              => 'cooca-pos-pro',
                'short_description' => 'Sistem Point of Sale (Kasir) berbasis cloud untuk manajemen penjualan, kasir, dan inventori stok barang.',
                'description'       => 'Sistem Point of Sale (Kasir) berbasis cloud terintegrasi untuk usaha retail, restoran, dan UMKM.',
                'is_active'         => true,
            ]
        );

        // 3. Find or Create 1-Month Plan
        $plan = SubscriptionPlan::firstOrCreate(
            [
                'product_id' => $product->id,
                'name'       => 'Paket 1 Bulan',
            ],
            [
                'slug'        => 'pos-pro-1-bulan',
                'price'       => 150000,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'is_active'   => true,
            ]
        );

        // 4. Create Dummy Unpaid Invoice / Transaction for Verification Testing if none pending
        $existingPending = Transaction::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if (!$existingPending) {
            $invNum = 'INV-MIDTRANS-' . strtoupper(Str::random(6));
            $transaction = Transaction::create([
                'customer_id'       => $customer->id,
                'type'              => 'subscription',
                'invoice_number'    => $invNum,
                'gross_amount'      => 150000,
                'voucher_discount'  => 0,
                'net_amount'        => 150000,
                'payment_gateway'   => 'midtrans',
                'midtrans_order_id' => $invNum,
                'status'            => 'pending',
            ]);

            Invoice::create([
                'transaction_id' => $transaction->id,
                'invoice_number' => $invNum,
                'customer_id'    => $customer->id,
                'amount'         => 150000,
                'status'         => Invoice::STATUS_ISSUED,
                'issued_at'      => now(),
                'due_at'         => now()->addDays(7),
            ]);
        }
    }
}
