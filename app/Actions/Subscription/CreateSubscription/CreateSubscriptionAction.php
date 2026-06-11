<?php

declare(strict_types=1);

namespace App\Actions\Subscription\CreateSubscription;

use App\Models\Subscription;
use App\DTOs\Subscription\SubscriptionData;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

final readonly class CreateSubscriptionAction
{
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}

    public function execute(SubscriptionData $data): Subscription
    {
        return $this->subscriptionRepository->create($data->toArray());
    }
}
