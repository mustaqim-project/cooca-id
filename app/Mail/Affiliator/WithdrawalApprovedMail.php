<?php
declare(strict_types=1);

namespace App\Mail\Affiliator;

use App\Traits\HasQueueConfiguration;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class WithdrawalApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly AffiliateWithdrawal $withdrawal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Withdrawal Disetujui - Rp ' . number_format($this->withdrawal->net_amount, 0, ',', '.'),
            tags: ['affiliate', 'withdrawal', 'approved'],
            metadata: [
                'affiliator_id' => $this->withdrawal->affiliator_id,
                'withdrawal_id' => $this->withdrawal->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.affiliator.withdrawal-approved',
            with: [
                'affiliatorName' => $this->withdrawal->affiliator->name,
                'amount' => number_format($this->withdrawal->amount, 0, ',', '.'),
                'fee' => number_format($this->withdrawal->fee, 0, ',', '.'),
                'netAmount' => number_format($this->withdrawal->net_amount, 0, ',', '.'),
                'withdrawalMethod' => ucfirst($this->withdrawal->withdrawal_method),
                'accountNumber' => $this->withdrawal->account_number,
                'accountName' => $this->withdrawal->account_name,
            ],
        );
    }
}
