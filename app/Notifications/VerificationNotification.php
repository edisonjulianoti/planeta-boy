<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $status,
        private readonly Profile $profile,
        private readonly ?string $rejectionReason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isApproved = $this->status === 'approved';

        $mail = (new MailMessage)
            ->subject($isApproved
                ? '✓ Documentos aprovados - Planeta Boy'
                : '✗ Documentos rejeitados - Planeta Boy')
            ->greeting('Olá ' . $notifiable->name . '!');

        if ($isApproved) {
            $mail
                ->line('Seus documentos de verificação foram **aprovados**!')
                ->line("Seu perfil \"{$this->profile->name}\" agora está com o selo de documentos verificados.")
                ->line('Isso aumenta a confiança dos seus clientes.')
                ->action('Ver meu perfil', route('perfil.ver', $this->profile->id));
        } else {
            $mail
                ->line('Seus documentos de verificação foram **rejeitados**.')
                ->line("Motivo: {$this->rejectionReason}")
                ->line('Você pode enviar novos documentos corrigindo o problema.')
                ->action('Enviar novos documentos', route('perfil.verificacao'));
        }

        return $mail
            ->salutation('Equipe Planeta Boy');
    }
}
