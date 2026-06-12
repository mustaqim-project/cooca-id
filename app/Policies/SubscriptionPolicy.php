<?php
declare(strict_types=1);

namespace App\Policies;

use App\Admin;
use App\Customer;
use App\Subscription;

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
