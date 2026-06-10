<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class AccountCreated extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bem-vindo ao Planeta Boys!')
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line('Sua conta foi criada com sucesso.')
            ->line('Agora você pode criar seu perfil e explorar o melhor da nossa plataforma.')
            ->action('Criar Perfil', route('perfil.criar'))
            ->line('Obrigado por se cadastrar!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Conta criada com sucesso!',
        ];
    }
}
