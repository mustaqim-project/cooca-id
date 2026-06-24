<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @template TModel of \App\Models\Admin
 */
final class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?Admin
    {
        return $this->model->where('email', $email)->first();
    }

    public function findWithPermissions(string $id): ?Admin
    {
        return $this->model->find($id);
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
        $query = $this->model->newQuery();

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function updatePermissions(string $id, array $permissions): bool
    {
        $admin = $this->find($id);

        if ($admin === null) {
            return false;
        }

        return $admin->update(['permissions' => $permissions]);
    }

    public function getActiveAdmins(): Collection
    {
        return $this->model->whereNotNull('email')->get();
    }

    public function findOrFail(string $id): Admin
    {
        return $this->model->findOrFail($id);
    }
}
