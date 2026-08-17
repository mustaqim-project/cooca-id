<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\Ticket;

final class TicketPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Affiliator|Customer $user, Ticket $ticket): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        if ($user instanceof Customer && $ticket->customer_id) {
            return $ticket->customer_id === $user->id;
        }

        if ($user instanceof Affiliator && $ticket->referred_by_id) {
            return $ticket->referred_by_id === $user->id;
        }

        return false;
    }

    public function create(Admin|Affiliator|Customer $user): bool
    {
        return true;
    }

    public function update(Admin|Affiliator|Customer $user, Ticket $ticket): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        if ($user instanceof Customer && $ticket->customer_id) {
            return $ticket->customer_id === $user->id;
        }

        if ($user instanceof Affiliator && $ticket->referred_by_id) {
            return $ticket->referred_by_id === $user->id;
        }

        return false;
    }

    public function resolve(Admin $admin): bool
    {
        return true;
    }

    public function close(Admin $admin): bool
    {
        return true;
    }

    public function delete(Admin $admin): bool
    {
        return true;
    }
}
