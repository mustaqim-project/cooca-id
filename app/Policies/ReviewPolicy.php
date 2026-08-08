<?php
declare(strict_types=1);

namespace App\Policies;

use App\Admin;
use App\Affiliator;
use App\Customer;
use App\Models\Review;

final class ReviewPolicy
{
    public function viewAny(?Admin $admin): bool
    {
        return true;
    }

    public function view(Admin|Affiliator|Customer $user, Review $review): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        if ($review->reviewer_type === 'customer' && $user instanceof Customer) {
            return $review->reviewer_id === $user->id;
        }

        if ($review->reviewer_type === 'affiliator' && $user instanceof Affiliator) {
            return $review->reviewer_id === $user->id;
        }

        return false;
    }

    public function create(Customer $user): bool
    {
        return true;
    }

    public function approve(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin): bool
    {
        return true;
    }

    public function delete(Admin|Affiliator|Customer $user, Review $review): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        if ($review->reviewer_type === 'customer' && $user instanceof Customer) {
            return $review->reviewer_id === $user->id;
        }

        if ($review->reviewer_type === 'affiliator' && $user instanceof Affiliator) {
            return $review->reviewer_id === $user->id;
        }

        return false;
    }
}
