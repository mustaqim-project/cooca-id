<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Affiliator;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * @template TModel of \App\Models\Affiliator
 */
final class AffiliatorRepository extends BaseRepository implements AffiliatorRepositoryInterface
{
    public function __construct(private readonly Affiliator $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?Affiliator
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByReferralCode(string $referralCode): ?Affiliator
    {
        return $this->model->where('referral_code', $referralCode)->first();
    }

    public function findWithParent(string $id): ?Affiliator
    {
        return $this->model->with('parent')->find($id);
    }

    public function emailExists(string $email, ?string $excludeId = null): bool
    {
        $query = $this->model->where('email', $email);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function paginateWithSearch(int $perPage = 15, ?string $search = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('parent');

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getDownlines(string $affiliatorId): Collection
    {
        return $this->model->where('parent_affiliator_id', $affiliatorId)->get();
    }

    public function getAllDownlines(string $affiliatorId): array
    {
        $downlines = [];
        $directDownlines = $this->getDownlines($affiliatorId);

        foreach ($directDownlines as $downline) {
            $downlines[] = $downline;
            $nestedDownlines = $this->getAllDownlines($downline->id);
            $downlines = array_merge($downlines, $nestedDownlines);
        }

        return $downlines;
    }

    public function countDownlines(string $affiliatorId): int
    {
        return $this->model->where('parent_affiliator_id', $affiliatorId)->count();
    }

    public function updateBalance(string $id, float $amount): bool
    {
        $affiliator = $this->find($id);

        if ($affiliator === null) {
            return false;
        }

        return $affiliator->update(['balance' => $amount]);
    }

    public function getTopAffiliators(int $limit = 10): Collection
    {
        return $this->model->orderByDesc('balance')->limit($limit)->get();
    }

    public function findOrFail(string $id): Affiliator
    {
        return $this->model->findOrFail($id);
    }
}
