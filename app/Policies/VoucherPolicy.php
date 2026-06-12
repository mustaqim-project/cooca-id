<?php
declare(strict_types=1);

namespace App\Policies;

use App\Admin;
use App\Models\Voucher;

final class VoucherPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin): bool
    {
        return true;
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
