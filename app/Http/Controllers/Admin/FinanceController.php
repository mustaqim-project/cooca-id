<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\MidtransTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class FinanceController extends Controller
{
    /**
     * Helper to calculate financial metrics for a transaction
     */
    private function calculateMetrics(Transaction $tx): array
    {
        $netAmount = (float) $tx->net_amount;
        $paymentType = $tx->midtransTransaction?->payment_type ?? 'unknown';

        $grossAmount = (float) $tx->gross_amount;
        $voucherDiscount = (float) $tx->voucher_discount;
        $subtotal = (float) ($tx->subtotal_amount > 0 ? $tx->subtotal_amount : max(0, $grossAmount - $voucherDiscount));
        $tax = (float) ($tx->tax_amount > 0 ? $tx->tax_amount : round($subtotal * 0.11, 2));

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

        $netProfit = $subtotal - $midtransFee - $affiliateCommission;

        return [
            'gross_amount' => $grossAmount,
            'voucher_discount' => $voucherDiscount,
            'subtotal' => $subtotal,
            'net_amount' => $netAmount,
            'tax' => $tax,
            'midtrans_fee' => $midtransFee,
            'affiliate_commission' => $affiliateCommission,
            'net_profit' => $netProfit,
            'payment_type' => $paymentType
        ];
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'midtransTransaction', 'commissions'])
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc');

        if ($request->has('month')) {
            $query->whereMonth('paid_at', $request->month);
        }
        if ($request->has('year')) {
            $query->whereYear('paid_at', $request->year);
        }

        $transactions = $query->paginate(20);
        
        // Summary metrics
        $summary = [
            'total_revenue' => 0,
            'total_tax' => 0,
            'total_fees' => 0,
            'total_commission' => 0,
            'total_profit' => 0,
        ];

        Transaction::with(['midtransTransaction', 'commissions'])
            ->where('status', 'paid')
            ->chunk(500, function ($paidChunk) use (&$summary) {
                foreach ($paidChunk as $tx) {
                    $metrics = $this->calculateMetrics($tx);
                    $summary['total_revenue'] += $metrics['net_amount'];
                    $summary['total_tax'] += $metrics['tax'];
                    $summary['total_fees'] += $metrics['midtrans_fee'];
                    $summary['total_commission'] += $metrics['affiliate_commission'];
                    $summary['total_profit'] += $metrics['net_profit'];
                }
            });

        // Attach metrics to paginated items
        $transactions->getCollection()->transform(function ($tx) {
            $tx->metrics = $this->calculateMetrics($tx);
            return $tx;
        });

        return view('admin.finance.index', compact('transactions', 'summary'));
    }

    public function export(Request $request)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="finance_report_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Invoice Number', 
                'Tanggal Dibayar', 
                'Customer', 
                'Metode Pembayaran', 
                'Gross Amount',
                'Voucher Discount',
                'Net Amount (Revenue)',
                'Tax (11%)',
                'Midtrans Fee',
                'Affiliate Commission',
                'Net Profit'
            ]);

            Transaction::with(['customer', 'midtransTransaction', 'commissions'])
                ->where('status', 'paid')
                ->orderBy('paid_at', 'desc')
                ->chunk(500, function ($transactions) use ($file) {
                    foreach ($transactions as $tx) {
                        $metrics = $this->calculateMetrics($tx);
                        fputcsv($file, [
                            $tx->invoice_number,
                            $tx->paid_at ? $tx->paid_at->format('Y-m-d H:i') : '',
                            $tx->customer->name ?? 'Unknown',
                            $metrics['payment_type'],
                            $metrics['gross_amount'],
                            $metrics['voucher_discount'],
                            $metrics['net_amount'],
                            $metrics['tax'],
                            $metrics['midtrans_fee'],
                            $metrics['affiliate_commission'],
                            $metrics['net_profit'],
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
