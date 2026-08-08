<?php

declare(strict_types=1);

namespace App\Mail\Admin;

use App\Traits\HasQueueConfiguration;
use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class SuspiciousLoginMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Admin $adminUser,
        private readonly string $ipAddress,
        private readonly string $timestamp,
        private readonly string $userAgent,
        private readonly string $securityUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 Admin Security Alert: Suspicious Login Activity',
            tags: ['admin', 'security', 'login', 'suspicious'],
            metadata: [
                'admin_user_id' => $this->adminUser->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin.suspicious-login',
            with: [
                'adminName' => $this->adminUser->name,
                'ipAddress' => $this->ipAddress,
                'timestamp' => $this->timestamp,
                'userAgent' => $this->userAgent,
                'securityUrl' => $this->securityUrl,
            ],
        );
    }
}
