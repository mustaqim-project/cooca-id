<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class PaymentReportController extends Controller
{
    /**
     * Display Payment Methods Report & Analytics.
     */
    public function index(Request $request)
    {
        // 1. Date Range Filters
        $preset = $request->input('preset', 'this_month');
        $startDate = null;
        $endDate = null;

        $now = Carbon::now();

        switch ($preset) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '7days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '30days':
                $startDate = $now->copy()->subDays(29)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;
            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                }
                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
            case 'all':
            default:
                // No date restriction
                break;
        }

        // 2. Base Query
        $baseQuery = Transaction::query()
            ->with(['customer', 'midtransTransaction', 'verifier'])
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->when($request->filled('gateway'), function ($q) use ($request) {
                if ($request->gateway === 'manual') {
                    $q->where(fn ($sub) => $sub->where('payment_gateway', 'manual')->orWhere('payment_method', 'bank_transfer_manual'));
                } elseif ($request->gateway === 'midtrans') {
                    $q->where(fn ($sub) => $sub->where('payment_gateway', 'midtrans')->orWhere('payment_method', 'midtrans'));
                }
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status));

        // 3. Overview Statistics
        $totalAllCount = (clone $baseQuery)->count();
        $totalPaidCount = (clone $baseQuery)->where('status', 'paid')->count();
        $totalPendingCount = (clone $baseQuery)->where('status', 'pending')->count();
        $totalFailedCount = (clone $baseQuery)->whereIn('status', ['failed', 'canceled', 'expire'])->count();

        $totalGrossPaid = (float) ((clone $baseQuery)->where('status', 'paid')->sum('gross_amount') ?: (clone $baseQuery)->where('status', 'paid')->sum('amount'));
        $totalNetPaid = (float) (clone $baseQuery)->where('status', 'paid')->sum('net_amount');
        $totalDiscountPaid = (float) (clone $baseQuery)->where('status', 'paid')->sum('voucher_discount');

        // 4. Breakdown By Gateway (Midtrans vs Manual Transfer)
        $midtransPaidCount = (clone $baseQuery)->where('status', 'paid')
            ->where(fn ($q) => $q->where('payment_gateway', 'midtrans')->orWhere('payment_method', 'midtrans'))
            ->count();
        $midtransNetRevenue = (float) (clone $baseQuery)->where('status', 'paid')
            ->where(fn ($q) => $q->where('payment_gateway', 'midtrans')->orWhere('payment_method', 'midtrans'))
            ->sum('net_amount');

        $manualPaidCount = (clone $baseQuery)->where('status', 'paid')
            ->where(fn ($q) => $q->where('payment_gateway', 'manual')->orWhere('payment_method', 'bank_transfer_manual'))
            ->count();
        $manualNetRevenue = (float) (clone $baseQuery)->where('status', 'paid')
            ->where(fn ($q) => $q->where('payment_gateway', 'manual')->orWhere('payment_method', 'bank_transfer_manual'))
            ->sum('net_amount');

        $midtransSharePercent = $totalNetPaid > 0 ? round(($midtransNetRevenue / $totalNetPaid) * 100, 1) : 0;
        $manualSharePercent = $totalNetPaid > 0 ? round(($manualNetRevenue / $totalNetPaid) * 100, 1) : 0;

        // 5. Channel Breakdown Matrix
        // A. Midtrans sub-types
        $midtransChannels = Transaction::query()
            ->join('midtrans_transactions', 'transactions.id', '=', 'midtrans_transactions.transaction_id')
            ->where('transactions.status', 'paid')
            ->when($startDate, fn ($q) => $q->where('transactions.created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('transactions.created_at', '<=', $endDate))
            ->select(
                'midtrans_transactions.payment_type',
                DB::raw('COUNT(transactions.id) as total_count'),
                DB::raw('SUM(transactions.net_amount) as total_revenue')
            )
            ->groupBy('midtrans_transactions.payment_type')
            ->get();

        // 6. Time Series Chart Data (Trend by Day / Month)
        $chartFormat = ($preset === 'this_year' || $preset === 'all') ? '%Y-%m' : '%Y-%m-%d';
        $chartGrouping = ($preset === 'this_year' || $preset === 'all') ? 'Y-m' : 'Y-m-d';

        $timeSeriesQuery = Transaction::query()
            ->where('status', 'paid')
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$chartFormat}') as period"),
                DB::raw("SUM(CASE WHEN payment_gateway = 'midtrans' OR payment_method = 'midtrans' THEN net_amount ELSE 0 END) as midtrans_amount"),
                DB::raw("SUM(CASE WHEN payment_gateway = 'manual' OR payment_method = 'bank_transfer_manual' THEN net_amount ELSE 0 END) as manual_amount")
            )
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        $chartLabels = $timeSeriesQuery->pluck('period')->toArray();
        $chartMidtrans = $timeSeriesQuery->pluck('midtrans_amount')->map(fn ($v) => (float) $v)->toArray();
        $chartManual = $timeSeriesQuery->pluck('manual_amount')->map(fn ($v) => (float) $v)->toArray();

        // 7. Paginated Transaction List for the table
        $transactions = (clone $baseQuery)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.reports.payments', [
            'transactions' => $transactions,
            'preset' => $preset,
            'startDate' => $startDate?->format('Y-m-d'),
            'endDate' => $endDate?->format('Y-m-d'),
            'stats' => [
                'total_all_count' => $totalAllCount,
                'total_paid_count' => $totalPaidCount,
                'total_pending_count' => $totalPendingCount,
                'total_failed_count' => $totalFailedCount,
                'total_gross_paid' => $totalGrossPaid,
                'total_net_paid' => $totalNetPaid,
                'total_discount_paid' => $totalDiscountPaid,
                'midtrans_paid_count' => $midtransPaidCount,
                'midtrans_net_revenue' => $midtransNetRevenue,
                'midtrans_share_percent' => $midtransSharePercent,
                'manual_paid_count' => $manualPaidCount,
                'manual_net_revenue' => $manualNetRevenue,
                'manual_share_percent' => $manualSharePercent,
            ],
            'midtransChannels' => $midtransChannels,
            'chartData' => [
                'labels' => $chartLabels,
                'midtrans' => $chartMidtrans,
                'manual' => $chartManual,
            ],
        ]);
    }

    /**
     * Export payment report to CSV.
     */
    public function export(Request $request)
    {
        $preset = $request->input('preset', 'all');
        $startDate = null;
        $endDate = null;
        $now = Carbon::now();

        switch ($preset) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '7days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '30days':
                $startDate = $now->copy()->subDays(29)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;
            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                }
                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        $query = Transaction::query()
            ->with(['customer', 'midtransTransaction', 'verifier'])
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->when($request->filled('gateway'), function ($q) use ($request) {
                if ($request->gateway === 'manual') {
                    $q->where(fn ($sub) => $sub->where('payment_gateway', 'manual')->orWhere('payment_method', 'bank_transfer_manual'));
                } elseif ($request->gateway === 'midtrans') {
                    $q->where(fn ($sub) => $sub->where('payment_gateway', 'midtrans')->orWhere('payment_method', 'midtrans'));
                }
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_jenis_pembayaran_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'Invoice Number',
                'Tanggal Transaksi',
                'Tanggal Lunas',
                'Nama Customer',
                'Email Customer',
                'Tipe Transaksi',
                'Metode Pembayaran (Gateway)',
                'Detail Saluran / Pengirim',
                'Status Pembayaran',
                'Gross Amount (IDR)',
                'Diskon Voucher (IDR)',
                'Net Amount / Pendapatan (IDR)',
                'Diverifikasi Oleh',
                'Catatan / Alasan Penolakan',
            ]);

            $query->chunk(500, function ($transactions) use ($file) {
                foreach ($transactions as $tx) {
                    $gateway = $tx->isManualTransfer() ? 'Transfer Bank Manual' : 'Midtrans Payment Gateway';
                    $channelDetail = $tx->isManualTransfer()
                        ? ($tx->sender_name ? 'a/n ' . $tx->sender_name : 'Transfer Manual')
                        : ($tx->midtransTransaction?->payment_type ?? 'Midtrans Instant');

                    fputcsv($file, [
                        $tx->invoice_number ?? ('INV-' . $tx->id),
                        $tx->created_at ? $tx->created_at->format('Y-m-d H:i:s') : '',
                        $tx->paid_at ? $tx->paid_at->format('Y-m-d H:i:s') : '',
                        $tx->customer->name ?? 'N/A',
                        $tx->customer->email ?? 'N/A',
                        ucfirst(str_replace('_', ' ', $tx->type ?? 'Subscription')),
                        $gateway,
                        $channelDetail,
                        strtoupper($tx->status),
                        (float) ($tx->gross_amount ?? $tx->amount ?? 0),
                        (float) ($tx->voucher_discount ?? 0),
                        (float) ($tx->net_amount ?? 0),
                        $tx->verifier->name ?? ($tx->status === 'paid' ? 'Sistem / Webhook' : '—'),
                        $tx->rejection_reason ?? $tx->payment_notes ?? '—',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
