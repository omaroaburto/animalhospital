<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage; 

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = config('app.frontend_url') . '?token=' . $this->token;
        /*
        return (new MailMessage)
            ->subject('Verifica tu cuenta en Animal Hospital')
            ->greeting('¡Bienvenido!')
            ->line('Gracias por registrarte. Solo falta un paso.')
            ->action('Verificar cuenta', $url)
            ->line('Si no creaste esta cuenta, puedes ignorar este correo.');*/
         return (new MailMessage)
            ->subject('Verifica tu cuenta en Animal Hospital')
            ->view('emails.verify-notification', [
                'url' => $url,
                'name' => $notifiable->name // $notifiable es el usuario que recibe el correo
            ]);
    }
}
