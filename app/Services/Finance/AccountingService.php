<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Models\Transaction;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

final class AccountingService
{
    /**
     * Calculate all financial metrics for a transaction
     */
    public function calculateMetrics(Transaction $tx): array
    {
        $netAmount = (float) $tx->net_amount;
        $paymentType = $tx->midtransTransaction?->payment_type ?? 'unknown';

        // Tax calculation: 11% PPN (tax inclusive logic from before)
        $tax = round($netAmount * 0.11, 2);

        // Affiliate commission
        $affiliateCommission = 0.0;
        if ($tx->commissions) {
            $affiliateCommission = (float) $tx->commissions->sum('commission_amount');
        }

        // Midtrans Fee calculation
        $midtransFee = 0.0;
        if (in_array($paymentType, ['bank_transfer', 'echannel'])) {
            $midtransFee = 4000.0;
        } elseif ($paymentType === 'qris') {
            $midtransFee = $netAmount * 0.007; // 0.7%
        } elseif (in_array($paymentType, ['gopay', 'shopeepay'])) {
            $midtransFee = $netAmount * 0.02; // 2%
        } elseif (in_array($paymentType, ['cstore'])) {
            $midtransFee = 5000.0;
        } elseif ($paymentType === 'credit_card') {
            $midtransFee = ($netAmount * 0.029) + 2000.0;
        } elseif (in_array($paymentType, ['akulaku'])) {
            $midtransFee = $netAmount * 0.017;
        } else {
            if ($paymentType === 'dana' || $paymentType === 'ovo') {
                $midtransFee = $netAmount * 0.015;
            } else {
                $midtransFee = $netAmount * 0.02; 
            }
        }

        $netProfit = $netAmount - $tax - $midtransFee - $affiliateCommission;

        return [
            'gross_amount' => (float) $tx->gross_amount,
            'voucher_discount' => (float) $tx->voucher_discount,
            'net_amount' => $netAmount,
            'tax' => $tax,
            'midtrans_fee' => $midtransFee,
            'affiliate_commission' => $affiliateCommission,
            'net_profit' => $netProfit,
            'payment_type' => $paymentType
        ];
    }

    /**
     * Automatically journal a successful Midtrans Payment
     */
    public function autoJournalMidtransPayment(Transaction $transaction): void
    {
        DB::beginTransaction();
        try {
            // Load required relationships if missing
            if (!$transaction->relationLoaded('midtransTransaction') || !$transaction->relationLoaded('commissions')) {
                $transaction->load(['midtransTransaction', 'commissions']);
            }

            $metrics = $this->calculateMetrics($transaction);

            // Get or create standard COA accounts
            $kasBankAccount = $this->getOrCreateCOA(1110, 'Kas/Bank', 1, 1, 'Akun utama penerimaan dana');
            $pendapatanAccount = $this->getOrCreateCOA(4110, 'Pendapatan Usaha', 4, 1, 'Akun pendapatan kotor');
            $biayaMidtransAccount = $this->getOrCreateCOA(5110, 'Biaya Admin & Midtrans', 5, 1, 'Akun biaya payment gateway');
            $biayaKomisiAccount = $this->getOrCreateCOA(5120, 'Biaya Komisi Afiliasi', 5, 1, 'Akun biaya komisi afiliator');
            $hutangPajakAccount = $this->getOrCreateCOA(2110, 'Hutang Pajak (PPN)', 2, 1, 'Akun hutang pajak keluaran');

            // Insert Journal Entry Header
            $journalId = DB::table('journal_entries')->insertGetId([
                'date' => Carbon::now()->toDateString(),
                'reference' => $transaction->invoice_number,
                'description' => 'Penerimaan pembayaran dari ' . ($transaction->customer->name ?? 'Customer') . ' (' . strtoupper($metrics['payment_type']) . ')',
                'journal_id' => 1, // General Journal
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $journalItems = [];

            // 1. Kas/Bank (Debit)
            $kasMasuk = $metrics['net_amount'] - $metrics['midtrans_fee'];

            $journalItems[] = [
                'journal' => $journalId,
                'account' => $kasBankAccount->id,
                'description' => 'Penerimaan dana (Settlement)',
                'debit' => $kasMasuk,
                'credit' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // 2. Biaya Admin Midtrans (Debit)
            if ($metrics['midtrans_fee'] > 0) {
                $journalItems[] = [
                    'journal' => $journalId,
                    'account' => $biayaMidtransAccount->id,
                    'description' => 'Potongan biaya Midtrans (' . strtoupper($metrics['payment_type']) . ')',
                    'debit' => $metrics['midtrans_fee'],
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // 3. Pendapatan (Credit)
            $pendapatanBersih = $metrics['net_amount'] - $metrics['tax'];

            $journalItems[] = [
                'journal' => $journalId,
                'account' => $pendapatanAccount->id,
                'description' => 'Pengakuan pendapatan kotor',
                'debit' => 0,
                'credit' => $pendapatanBersih,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // 4. Hutang PPN (Credit)
            if ($metrics['tax'] > 0) {
                $journalItems[] = [
                    'journal' => $journalId,
                    'account' => $hutangPajakAccount->id,
                    'description' => 'Pengakuan hutang PPN 11%',
                    'debit' => 0,
                    'credit' => $metrics['tax'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // 5. Pencatatan Biaya Komisi (Accrual)
            if ($metrics['affiliate_commission'] > 0) {
                $hutangKomisiAccount = $this->getOrCreateCOA(2120, 'Hutang Komisi Afiliasi', 2, 1, 'Akun hutang ke afiliator');
                
                $journalItems[] = [
                    'journal' => $journalId,
                    'account' => $biayaKomisiAccount->id,
                    'description' => 'Pengakuan biaya komisi',
                    'debit' => $metrics['affiliate_commission'],
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $journalItems[] = [
                    'journal' => $journalId,
                    'account' => $hutangKomisiAccount->id,
                    'description' => 'Hutang komisi belum dibayar',
                    'debit' => 0,
                    'credit' => $metrics['affiliate_commission'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('journal_items')->insert($journalItems);

            DB::commit();

            Log::info('AccountingService: Auto Journal completed', [
                'transaction_id' => $transaction->id,
                'journal_id' => $journalId,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AccountingService: Failed to auto-journal payment', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function getOrCreateCOA(int $code, string $name, int $type, int $subType, string $description): ChartOfAccount
    {
        $coa = ChartOfAccount::where('code', $code)->first();
        if (!$coa) {
            $coa = ChartOfAccount::create([
                'code' => $code,
                'name' => $name,
                'type' => $type,
                'sub_type' => $subType,
                'is_enabled' => 1,
                'description' => $description,
            ]);
        }
        return $coa;
    }
}
