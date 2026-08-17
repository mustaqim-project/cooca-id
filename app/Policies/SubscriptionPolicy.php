<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Subscription;

final class SubscriptionPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Customer $user, Subscription $subscription): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $subscription->customer_id === $user->id;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin|Customer $user, Subscription $subscription): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $subscription->customer_id === $user->id;
    }

    public function delete(Admin $admin): bool
    {
        return true;
    }

    public function cancel(Admin|Customer $user, Subscription $subscription): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $subscription->customer_id === $user->id;
    }
}
