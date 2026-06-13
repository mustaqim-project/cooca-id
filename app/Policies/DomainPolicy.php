<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Domain;

final class DomainPolicy
{
    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $admin, Domain $domain): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can update the domain status.
     */
    public function update(Admin $admin, Domain $domain): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can mark domain as verified.
     */
    public function verify(Admin $admin, Domain $domain): bool
    {
        return in_array($domain->status, [
            Domain::STATUS_PENDING,
            Domain::STATUS_VERIFICATION_REQUIRED,
        ], true);
    }

    /**
     * Determine whether the admin can mark domain as in setup.
     */
    public function markInSetup(Admin $admin, Domain $domain): bool
    {
        return in_array($domain->status, [
            Domain::STATUS_PENDING,
            Domain::STATUS_VERIFICATION_REQUIRED,
            Domain::STATUS_WAITING_SETUP,
        ], true);
    }

    /**
     * Determine whether the admin can activate the domain.
     */
    public function activate(Admin $admin, Domain $domain): bool
    {
        return in_array($domain->status, [
            Domain::STATUS_IN_SETUP,
            Domain::STATUS_WAITING_SETUP,
        ], true);
    }

    /**
     * Determine whether the admin can mark domain as failed.
     */
    public function markFailed(Admin $admin, Domain $domain): bool
    {
        return !in_array($domain->status, [
            Domain::STATUS_ACTIVE,
            Domain::STATUS_FAILED,
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
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, Domain $domain): bool
    {
        return false; // Domains should not be deleted
    }

    /**
     * Determine whether the admin can restore the model.
     */
    public function restore(Admin $admin, Domain $domain): bool
    {
        return false;
    }

    /**
     * Determine whether the admin can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Domain $domain): bool
    {
        return false;
    }
}
