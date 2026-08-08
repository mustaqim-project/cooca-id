<?php

declare(strict_types=1);

namespace App\Mail\Customer;

use App\Traits\HasQueueConfiguration;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ProjectMilestonePaymentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Customer $customer,
        private readonly Project $project,
        private readonly Transaction $transaction,
        private readonly string $paymentUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tagihan Termin Baru Proyek: ' . $this->project->project_name,
            tags: ['project', 'billing', 'invoice'],
            metadata: [
                'customer_id' => $this->customer->id,
                'project_id' => $this->project->id,
                'transaction_id' => $this->transaction->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.project-milestone-payment',
            with: [
                'customerName' => $this->customer->name,
                'projectName' => $this->project->project_name,
                'invoiceNumber' => $this->transaction->invoice_number,
                'description' => $this->transaction->description,
                'amount' => $this->transaction->net_amount,
                'paymentUrl' => $this->paymentUrl,
            ],
        );
    }
}
