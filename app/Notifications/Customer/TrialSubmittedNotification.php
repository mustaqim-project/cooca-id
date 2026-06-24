<?php

declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Traits\HasQueueConfiguration;

use App\Models\ErpRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TrialSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private readonly ErpRequest $erpRequest,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Trial Request Submitted - COOCA.ID')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your ERP trial request has been submitted successfully.')
            ->line('Our team will review your request and get back to you soon.')
            ->line('Request Details:')
            ->line('- Product: ' . $this->erpRequest->product->name)
            ->line('- Requested Domain: ' . ($this->erpRequest->requested_domain ?? $this->erpRequest->requested_subdomain))
            ->line('You will receive another email once your request is approved and setup is complete.')
            ->salutation('Thank you for choosing COOCA.ID');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_submitted',
            'erp_request_id' => $this->erpRequest->id,
            'product_name' => $this->erpRequest->product->name,
            'message' => 'Your ERP trial request has been submitted successfully.',
        ];
    }
}
