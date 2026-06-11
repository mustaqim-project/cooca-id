<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\AffiliateCommission;
use App\Repositories\Contracts\AffiliateCommissionRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

final class AffiliateCommissionRepository extends BaseRepository implements AffiliateCommissionRepositoryInterface
{
    public function __construct(AffiliateCommission $model)
    {
        parent::__construct($model);
    }

    public function findByTransactionId(string $transactionId): Collection
    {
        return $this->model
            ->where('transaction_id', $transactionId)
            ->with(['affiliator', 'transaction', 'customer'])
            ->get();
    }

    public function findByAffiliatorId(string $affiliatorId, int $limit = 50): Collection
    {
        return $this->model
            ->where('affiliator_id', $affiliatorId)
            ->with(['transaction', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPendingsByAffiliator(string $affiliatorId): Collection
    {
        return $this->model
            ->where('affiliator_id', $affiliatorId)
            ->where('status', 'pending')
            ->with(['transaction', 'customer'])
            ->orderBy('created_at')
            ->get();
    }

    public function getClearedByAffiliator(string $affiliatorId): Collection
    {
        return $this->model
            ->where('affiliator_id', $affiliatorId)
            ->where('status', 'cleared')
            ->with(['transaction', 'customer'])
            ->orderBy('cleared_at', 'desc')
            ->get();
    }

    public function getTotalPendingBalance(string $affiliatorId): float
    {
        return $this->model
            ->where('affiliator_id', $affiliatorId)
            ->where('status', 'pending')
            ->sum('commission_amount');
    }

    public function getTotalClearedBalance(string $affiliatorId): float
    {
        return $this->model
            ->where('affiliator_id', $affiliatorId)
            ->where('status', 'cleared')
            ->sum('commission_amount');
    }

    public function markAsCleared(string $commissionId): bool
    {
        $commission = $this->model->find($commissionId);
        
        if (!$commission) {
            return false;
        }

        $commission->update([
            'status' => 'cleared',
            'cleared_at' => Carbon::now(),
        ]);

        return true;
    }

    public function markAsCancelled(string $commissionId): bool
    {
        $commission = $this->model->find($commissionId);
        
        if (!$commission) {
            return false;
        }

        $commission->update([
            'status' => 'cancelled',
        ]);

        return true;
    }

    public function getCommissionsByDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->model
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['affiliator', 'transaction', 'customer'])
            ->orderBy('created_at')
            ->get();
    }

    public function getCommissionsByLevel(string $affiliatorId, int $level): Collection
    {
        return $this->model
            ->where('affiliator_id', $affiliatorId)
            ->where('level', $level)
            ->with(['transaction', 'customer'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
