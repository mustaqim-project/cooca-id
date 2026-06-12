<?php
declare(strict_types=1);

namespace App\Policies;

use App\Admin;
use App\Affiliator;
use App\Models\AffiliateCommission;

final class AffiliateCommissionPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Affiliator $user, AffiliateCommission $commission): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $commission->affiliator_id === $user->id;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin): bool
    {
        return true;
    }

    public function delete(Admin $admin): bool
    {
        return true;
    }
}
