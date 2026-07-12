<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Email de réinitialisation de mot de passe pour le dashboard web ABBEV.
 * En français, pointant vers la route admin.password.reset. L'expéditeur (From)
 * et le mailer sont ceux configurés dans l'onglet Email de la Configuration.
 */
class AdminResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expire = config('auth.passwords.'.config('auth.defaults.passwords', 'users').'.expire', 60);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — ABBEV')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email car une réinitialisation du mot de passe de votre compte ABBEV a été demandée.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line("Ce lien expirera dans {$expire} minutes.")
            ->line("Si vous n'êtes pas à l'origine de cette demande, aucune action n'est requise — ignorez simplement cet email.")
            ->salutation("L'équipe ABBEV");
    }
}
