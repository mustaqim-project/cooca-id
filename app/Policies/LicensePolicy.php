<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\License;

final class LicensePolicy
{
    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the admin or customer can view the model.
     */
    public function view(Admin|Customer $user, License $license): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $license->customer_id === $user->id;
    }

    /**
     * Determine whether the admin can activate a license.
     */
    public function activate(Admin $admin, License $license): bool
    {
        return $license->status === License::STATUS_INACTIVE;
    }

    /**
     * Determine whether the admin can suspend a license.
     */
    public function suspend(Admin $admin, License $license): bool
    {
        return $license->status === License::STATUS_ACTIVE;
    }

    /**
     * Determine whether the admin can revoke a license.
     */
    public function revoke(Admin $admin, License $license): bool
    {
        return in_array($license->status, [
            License::STATUS_ACTIVE,
            License::STATUS_EXPIRED,
        ], true);
    }

    /**
     * Determine whether the admin can create a new model.
     */
    public function create(Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, License $license): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, License $license): bool
    {
        return false; // Licenses should not be deleted, only revoked
    }

    /**
     * Determine whether the admin can restore the model.
     */
    public function restore(Admin $admin, License $license): bool
    {
        return false;
    }

    /**
     * Determine whether the admin can permanently delete the model.
     */
    public function forceDelete(Admin $admin, License $license): bool
    {
        return false;
    }
}
