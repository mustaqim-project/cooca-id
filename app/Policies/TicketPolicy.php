<?php
declare(strict_types=1);

namespace App\Policies;

use App\Admin;
use App\Affiliator;
use App\Customer;
use App\Models\Ticket;

final class TicketPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Customer|Affiliator $user, Ticket $ticket): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        if ($user instanceof Customer && $ticket->customer_id) {
            return $ticket->customer_id === $user->id;
        }

        if ($user instanceof Affiliator && $ticket->affiliator_id) {
            return $ticket->affiliator_id === $user->id;
        }

        return false;
    }

    public function create(Customer|Affiliator $user): bool
    {
        return true;
    }

    public function update(Admin|Customer|Affiliator $user, Ticket $ticket): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        if ($user instanceof Customer && $ticket->customer_id) {
            return $ticket->customer_id === $user->id;
        }

        if ($user instanceof Affiliator && $ticket->affiliator_id) {
            return $ticket->affiliator_id === $user->id;
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
