<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriptionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $planSlug,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nova solicitacao de assinatura - Planeta Boys')
            ->greeting('Nova solicitacao de assinatura!')
            ->line("O usuario {$this->user->name} ({$this->user->email}) solicitou o plano: {$this->planSlug}.")
            ->action('Gerenciar Assinaturas', url(route('admin.subscriptions')))
            ->line('Acesse o painel admin para aprovar ou rejeitar.');
    }
}
