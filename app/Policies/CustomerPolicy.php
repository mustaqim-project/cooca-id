<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Customer $model): bool
    {
        return true;
    }

    public function create(Admin $user): bool
    {
        return true;
    }

    public function update(Admin $user, Customer $model): bool
    {
        return true;
    }

    public function delete(Admin $user, Customer $model): bool
    {
        return true;
    }
}
