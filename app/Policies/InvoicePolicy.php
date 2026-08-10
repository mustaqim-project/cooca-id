<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Invoice;

final class InvoicePolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Customer $user, Invoice $invoice): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $invoice->customer_id === $user->id;
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
