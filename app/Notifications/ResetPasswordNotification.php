<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Скидання пароля')
            ->greeting('Привіт!')
            ->line('Ви отримали цей лист, тому що надійшов запит на скидання пароля для вашого акаунту.')
            ->action('Скинути пароль', url(route('password.reset', $this->token, false)))
            ->line('Цей лінк буде дійсний протягом 60 хвилин.')
            ->line('Якщо ви не запитували зміну пароля, ніяких дій не потрібно.')
            ->salutation('З повагою, Laravel');
    }
}
