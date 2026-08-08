<?php
declare(strict_types=1);

namespace App\Mail\Affiliator;

use App\Traits\HasQueueConfiguration;
use App\Models\Affiliator;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class CommissionReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly Affiliator $affiliator,
        private readonly float $commissionAmount,
        private readonly int $level,
        private readonly float $newBalance,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Komisi Diterima - Rp ' . number_format($this->commissionAmount, 0, ',', '.'),
            tags: ['affiliate', 'commission'],
            metadata: [
                'referred_by_id' => $this->affiliator->id,
                'commission_amount' => $this->commissionAmount,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.affiliator.commission-received',
            with: [
                'affiliatorName' => $this->affiliator->name,
                'commissionAmount' => number_format($this->commissionAmount, 0, ',', '.'),
                'level' => $this->level,
                'newBalance' => number_format($this->newBalance, 0, ',', '.'),
            ],
        );
    }
}
