<?php
declare(strict_types=1);

namespace App\Mail\Affiliator;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class WithdrawalRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly AffiliateWithdrawal $withdrawal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Withdrawal Ditolak',
            tags: ['affiliate', 'withdrawal', 'rejected'],
            metadata: [
                'affiliator_id' => $this->withdrawal->affiliator_id,
                'withdrawal_id' => $this->withdrawal->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.affiliator.withdrawal-rejected',
            with: [
                'affiliatorName' => $this->withdrawal->affiliator->name,
                'amount' => number_format($this->withdrawal->amount, 0, ',', '.'),
                'rejectionReason' => $this->withdrawal->rejection_reason ?? 'Tidak ada alasan yang diberikan',
            ],
        );
    }
}
