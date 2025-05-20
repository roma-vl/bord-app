<?php

namespace App\Notifications\Advert;

use App\Models\Adverts\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModerationPassedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Advert $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {

        return (new MailMessage)
            ->subject('Moderation is passed')
            ->greeting('Hello!')
            ->line('Your advert "'.$this->advert->title.'" successfully passed moderation.')
            ->action('View Advert', route('adverts.show', $this->advert->id))
            ->line('Thank you for using our application!');
    }
}
