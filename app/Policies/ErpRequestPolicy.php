<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\ErpRequest;

final class ErpRequestPolicy
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
    public function view(Admin $admin, ErpRequest $erpRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can approve requests.
     */
    public function approve(Admin $admin, ErpRequest $erpRequest): bool
    {
        return $erpRequest->status === ErpRequest::STATUS_WAITING_APPROVAL;
    }

    /**
     * Determine whether the admin can reject requests.
     */
    public function reject(Admin $admin, ErpRequest $erpRequest): bool
    {
        return in_array($erpRequest->status, [
            ErpRequest::STATUS_SUBMITTED,
            ErpRequest::STATUS_WAITING_APPROVAL,
        ], true);
    }

    /**
     * Determine whether the admin can update the status to waiting setup.
     */
    public function markWaitingSetup(Admin $admin, ErpRequest $erpRequest): bool
    {
        return $erpRequest->status === ErpRequest::STATUS_WAITING_APPROVAL;
    }

    /**
     * Determine whether the admin can mark the request as in setup.
     */
    public function markInSetup(Admin $admin, ErpRequest $erpRequest): bool
    {
        return $erpRequest->status === ErpRequest::STATUS_WAITING_SETUP;
    }

    /**
     * Determine whether the admin can mark domain setup.
     */
    public function markDomainSetup(Admin $admin, ErpRequest $erpRequest): bool
    {
        return $erpRequest->status === ErpRequest::STATUS_IN_SETUP;
    }

    /**
     * Determine whether the admin can mark testing.
     */
    public function markTesting(Admin $admin, ErpRequest $erpRequest): bool
    {
        return $erpRequest->status === ErpRequest::STATUS_DOMAIN_SETUP;
    }

    /**
     * Determine whether the admin can confirm ready and activate trial.
     */
    public function confirmReady(Admin $admin, ErpRequest $erpRequest): bool
    {
        return $erpRequest->status === ErpRequest::STATUS_TESTING;
    }

    /**
     * Determine whether the admin can create a new model.
     */
    public function create(Admin $admin): bool
    {
        return false; // ERP requests are created by customers only
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, ErpRequest $erpRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, ErpRequest $erpRequest): bool
    {
        return false; // ERP requests should not be deleted, only rejected
    }

    /**
     * Determine whether the admin can restore the model.
     */
    public function restore(Admin $admin, ErpRequest $erpRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the admin can permanently delete the model.
     */
    public function forceDelete(Admin $admin, ErpRequest $erpRequest): bool
    {
        return false;
    }
}
