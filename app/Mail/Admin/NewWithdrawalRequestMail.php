<?php
declare(strict_types=1);

namespace App\Mail\Admin;

use App\Traits\HasQueueConfiguration;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class NewWithdrawalRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly AffiliateWithdrawal $withdrawal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Withdrawal Baru - Rp ' . number_format($this->withdrawal->amount, 0, ',', '.'),
            tags: ['admin', 'withdrawal', 'request'],
            metadata: [
                'withdrawal_id' => $this->withdrawal->id,
                'affiliator_id' => $this->withdrawal->affiliator_id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin.new-withdrawal-request',
            with: [
                'affiliatorName' => $this->withdrawal->affiliator->name,
                'affiliatorEmail' => $this->withdrawal->affiliator->email,
                'amount' => number_format($this->withdrawal->amount, 0, ',', '.'),
                'fee' => number_format($this->withdrawal->fee, 0, ',', '.'),
                'netAmount' => number_format($this->withdrawal->net_amount, 0, ',', '.'),
                'withdrawalMethod' => ucfirst($this->withdrawal->withdrawal_method),
                'accountNumber' => $this->withdrawal->account_number,
                'accountName' => $this->withdrawal->account_name,
                'requestedAt' => $this->withdrawal->created_at->format('d F Y H:i'),
                'approvalUrl' => route('admin.withdrawals.show', $this->withdrawal->id),
            ],
        );
    }
}
