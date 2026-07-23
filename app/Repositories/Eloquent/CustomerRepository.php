<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * @template TModel of \App\Models\Customer
 */
final class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?Customer
    {
        return $this->model->where('email', $email)->first();
    }

    public function findWithAffiliator(string $id): ?Customer
    {
        return $this->model->with('affiliator')->find($id);
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
        $query = $this->model->newQuery()->with('affiliator');

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getByAffiliator(string $affiliatorId): Collection
    {
        return $this->model->where('referred_by_id', $affiliatorId)->get();
    }

    public function countByAffiliator(string $affiliatorId): int
    {
        return $this->model->where('referred_by_id', $affiliatorId)->count();
    }

    public function getActiveCustomers(): Collection
    {
        return $this->model->whereNotNull('email')->get();
    }
}
