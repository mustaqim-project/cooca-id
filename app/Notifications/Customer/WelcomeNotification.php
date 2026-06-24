<?php
declare(strict_types=1);

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Traits\HasQueueConfiguration;

final class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;


    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Selamat Bergabung di COOCA.ID!')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Selamat datang di COOCA.ID!')
            ->line('Terima kasih telah bergabung dengan kami. Anda sekarang dapat mengakses berbagai sistem ERP profesional untuk bisnis Anda.')
            ->action('Login Sekarang', route('customer.login'))
            ->line('Jika Anda memiliki pertanyaan, tim support kami siap membantu 24/7.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'welcome',
            'message' => 'Selamat bergabung di COOCA.ID! Mulai jelajahi dashboard untuk menggunakan layanan.',
        ];
    }
}
