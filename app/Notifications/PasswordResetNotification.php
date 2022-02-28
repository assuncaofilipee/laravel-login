<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification  implements ShouldQueue
{
    use Queueable;

    protected $password_token;
    /**
     * Criação de nova instância
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->password_token = $code;
    }

    /**
     * Canal a ser enviado.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Email de recuperação de senha
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->greeting('Ola!')
        ->line('A recuperação de senha para a conta associada a esse email foi requisitada.')
        ->line('Por favor insira o código abaixo na página de recuperação de senha')
        ->line($this->password_token)
        ->line('Se você não requisitou a recuperação de senha, por favor ignore essa mensaggem.')
        ->subject('Trouw - Recuperação de senha');
    }
}
