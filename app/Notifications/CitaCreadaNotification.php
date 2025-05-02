<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Cita;

class CitaCreadaNotification extends Notification
{
    use Queueable;

    public $cita;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nueva Cita Creada')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Has creado una nueva cita con los siguientes detalles:')
                    ->line('Título: ' . $this->cita->titulo)
                    ->line('Fecha y Hora: ' . $this->cita->fecha_de_la_cita->format('d/m/Y H:i'))
                    ->line('Descripción: ' . ($this->cita->descripcion ?? 'Sin descripción'))
                    ->line('Estado: Pendiente')
                    ->line('Gracias por utilizar nuestro sistema de citas.');
    }
}
