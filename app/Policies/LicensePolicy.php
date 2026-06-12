<?php
declare(strict_types=1);

namespace App\Policies;

use App\Admin;
use App\Customer;
use App\License;

final class LicensePolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Customer $user, License $license): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $license->customer_id === $user->id;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin): bool
    {
        return true;
    }

    public function revoke(Admin $admin): bool
    {
        return true;
    }

    public function delete(Admin $admin): bool
    {
        return true;
    }
}
