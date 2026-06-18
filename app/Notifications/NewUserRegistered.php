<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $whatsapp = $this->user->phone ?? 'Não informado';

        return (new MailMessage)
            ->subject('Novo usuário cadastrado - Planeta Boys')
            ->greeting('Novo cadastro na plataforma!')
            ->line("Um novo usuário acabou de se cadastrar:")
            ->line("Nome: {$this->user->name}")
            ->line("E-mail: {$this->user->email}")
            ->line("WhatsApp: {$whatsapp}")
            ->line("Cadastrado em: {$this->user->created_at->format('d/m/Y H:i')}")
            ->action('Gerenciar Usuários', url(route('admin.dashboard')))
            ->line('Acesse o painel admin para mais detalhes.');
    }
}
