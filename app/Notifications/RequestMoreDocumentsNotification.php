<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket; 
use Illuminate\Support\Facades\Auth;

class RequestMoreDocumentsNotification extends Notification
{
    use Queueable;

    public Ticket $ticket;
    public string $messageContent;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket           The ticket instance.
     * @param string $messageContent The message from the agent.
     */
    public function __construct(Ticket $ticket, string $messageContent)
    {
        $this->ticket = $ticket;
        $this->messageContent = $messageContent;
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
     *
     * @param object $notifiable The user who created the ticket.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Solicitud de Documentos Adicionales para su Ticket #' . $this->ticket->id)
                    ->greeting('Hola, ' . $notifiable->name . '!')
                    ->line('Tu ticket **#' . $this->ticket->id . ' - ' . $this->ticket->name . '** requiere documentos adicionales.')
                    ->line('**Mensaje del agente asignado:**')
                    ->line($this->messageContent)
                    ->action('Ver Tu Ticket', url('/user/my-tickets/' . $this->ticket->id)) // Asume una ruta para ver tickets de usuario
                    ->line('Por favor, adjunta los documentos solicitados lo antes posible para poder continuar procesando tu solicitud.')
                    ->line('Gracias por tu colaboración.');
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
            'message_content' => $this->messageContent,
            'requested_by_agent_id' => Auth::id(),
        ];
    }
}
