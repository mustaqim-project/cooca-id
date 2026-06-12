<?php
declare(strict_types=1);

namespace App\Observers;

use App\Events\User\CustomerRegistered;
use App\Models\Customer;

final class CustomerObserver
{
    public function created(Customer $customer): void
    {
        event(new CustomerRegistered($customer));
    }
}
