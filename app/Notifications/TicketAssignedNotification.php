<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketAssignedNotification extends Notification
{
    use Queueable;
    public Ticket $ticket;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuevo Ticket Asignado: ' . $this->ticket->name) // Asunto del correo
            ->greeting('Hola, ' . $notifiable->name . '!') // Saludo al agente
            ->line('Se te ha asignado un nuevo ticket:') // Línea de texto
            ->line('**ID del Ticket:** ' . $this->ticket->id)
            ->line('**Título:** ' . $this->ticket->name)
            ->line('**Descripción:** ' . $this->ticket->description)
            ->line('**Creado por:** ' . ($this->ticket->user->name ?? 'Usuario Desconocido')) // Accede a la relación 'user'
            ->action('Ver Ticket', url('/agent/tickets/' . $this->ticket->id)) // Botón de acción para ver el ticket
            ->line('Gracias por tu atención.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_name' => $this->ticket->name,
            'assigned_by_user_id' => Auth::id(), // Quién lo asignó (el usuario que creó el ticket)
        ];
    }
}
