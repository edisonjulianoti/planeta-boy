<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Profile $profile,
        public string $comment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo comentario no Planeta Boys')
            ->greeting('Novo comentario recebido!')
            ->line("Um novo comentario foi enviado no perfil de {$this->profile->name}:")
            ->line("'{$this->comment}'")
            ->action('Ver Perfil', url(route('perfil.ver', $this->profile->id)))
            ->line('Acesse o painel admin para gerenciar.');
    }
}
