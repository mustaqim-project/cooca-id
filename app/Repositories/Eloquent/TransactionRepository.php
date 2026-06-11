<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

final class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function findByInvoiceNumber(string $invoiceNumber): ?Transaction
    {
        return $this->model
            ->where('invoice_number', $invoiceNumber)
            ->with(['customer', 'subscription', 'voucher'])
            ->first();
    }

    public function getTransactionsByCustomer(string $customerId, int $limit = 20): Collection
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->with(['subscription.license.product', 'voucher'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPendingTransactions(): Collection
    {
        return $this->model
            ->where('status', 'pending')
            ->with(['customer', 'subscription'])
            ->orderBy('created_at')
            ->get();
    }

    public function getPaidTransactionsByDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->model
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['customer', 'subscription.license.product'])
            ->orderBy('paid_at', 'desc')
            ->get();
    }

    public function getTotalRevenueByDateRange(Carbon $startDate, Carbon $endDate): float
    {
        return $this->model
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('net_amount');
    }

    public function getGrossAmountTotalByDateRange(Carbon $startDate, Carbon $endDate): float
    {
        return $this->model
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('gross_amount');
    }

    public function getTransactionsForCommissionCalculation(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->model
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->whereNotNull('customer_id')
            ->with(['customer.affiliatorParent'])
            ->get();
    }

    public function markAsPaid(string $transactionId, string $midtransTransactionId, string $midtransStatus): bool
    {
        $transaction = $this->model->find($transactionId);
        
        if (!$transaction) {
            return false;
        }

        $transaction->update([
            'status' => 'paid',
            'midtrans_transaction_id' => $midtransTransactionId,
            'midtrans_status' => $midtransStatus,
            'paid_at' => Carbon::now(),
        ]);

        return true;
    }

    public function markAsFailed(string $transactionId, string $midtransTransactionId, string $midtransStatus): bool
    {
        $transaction = $this->model->find($transactionId);
        
        if (!$transaction) {
            return false;
        }

        $transaction->update([
            'status' => 'failed',
            'midtrans_transaction_id' => $midtransTransactionId,
            'midtrans_status' => $midtransStatus,
            'failed_at' => Carbon::now(),
        ]);

        return true;
    }
}
