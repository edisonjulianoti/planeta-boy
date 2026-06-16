<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Confirme seu e-mail - Planeta Boy')
            ->greeting('Ola ' . $notifiable->name . '!')
            ->line('Obrigado por criar sua conta no Planeta Boy.')
            ->line('Para comecar, confirme seu endereco de e-mail clicando no botao abaixo:')
            ->action('Confirmar E-mail', $verificationUrl)
            ->line('Se voce nao criou esta conta, ignore este e-mail.')
            ->salutation('Equipe Planeta Boy');
    }
}
