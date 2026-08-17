<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\AffiliateWithdrawal;

final class AffiliateWithdrawalPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Affiliator $user, AffiliateWithdrawal $withdrawal): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $withdrawal->referred_by_id === $user->id;
    }

    public function create(Affiliator $affiliator): bool
    {
        return true;
    }

    public function approve(Admin $admin): bool
    {
        return true;
    }

    public function reject(Admin $admin): bool
    {
        return true;
    }

    public function pay(Admin $admin): bool
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
