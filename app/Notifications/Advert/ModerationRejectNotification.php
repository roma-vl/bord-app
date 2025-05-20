<?php

namespace App\Notifications\Advert;

use App\Models\Adverts\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModerationRejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Advert $advert;

    private string $reject_reason;

    public function __construct(Advert $advert, $reject_reason)
    {
        $this->advert = $advert;
        $this->reject_reason = $reject_reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Moderation is reject')
            ->greeting('Hello!')
            ->line('Your advert "'.$this->advert->title.'" reject moderation.')
            ->line('Reason for refusal "'.$this->reject_reason.'".')
            ->action('View Advert', route('adverts.show', $this->advert->id))
            ->line('Thank you for using our application!');
    }
}
