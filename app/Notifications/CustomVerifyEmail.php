<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Підтвердіть ваш email') // Зміни заголовок
            ->greeting('Привіт! 👋') // Додай привітання
            ->line('Будь ласка, натисніть кнопку нижче, щоб підтвердити вашу електронну адресу.')
            ->action('Підтвердити Email', $verificationUrl)
            ->line('Якщо ви не створювали акаунт, просто ігноруйте цей лист.')
            ->salutation('З повагою, Команда Laravel'); // Додай підпис
    }
}
