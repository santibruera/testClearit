<?php

namespace App\Livewire\Agent;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Notifications\RequestMoreDocumentsNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TicketDetails extends Component
{
    public Ticket $ticket;
    public $ticketId;
    public $newStatusId;
    public $ticketStatuses;
    public $requestDocumentMessage = '';
   
    protected function rules()
    {
        return [
            'newStatusId' => ['required', Rule::exists('ticket_statuses', 'id')],
            'requestDocumentMessage' => 'required|string|min:10',
        ];
    }

    public function mount($ticketId)
    {
   
        $this->ticketId = $ticketId;
        $this->loadTicket();
        $this->ticketStatuses = TicketStatus::all();
        $this->newStatusId = $this->ticket->status;
    }

    protected function loadTicket()
    {
        $this->ticket = Ticket::with([
            'tipoTicket',
            'modoTransporte',
            'producto',
            'ticketStatus',
            'user',
            'assignedUser',
            'attachments'
        ])->findOrFail($this->ticketId);
    }

    public function updateStatus()
    {
        if (!Auth::user()->hasRole('agent') || Auth::id() !== $this->ticket->assignedTo) {
            session()->flash('error', 'No tienes permiso para actualizar el estado de este ticket.');
            return;
        }

        $this->validate(['newStatusId' => $this->rules()['newStatusId']]);

        try {
            $this->ticket->update(['status' => $this->newStatusId]);
            $this->loadTicket(); // Recargar el ticket para reflejar el nuevo estado en la vista

            session()->flash('message', 'Estado del ticket actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el estado: ' . $e->getMessage());
           
        }
    }

    /**
     * Envía una notificación al creador del ticket solicitando más documentos.
     */
    public function requestMoreDocuments()
    {
        
        if (!Auth::user()->hasRole('agent') || Auth::id() !== $this->ticket->assignedTo) {
            session()->flash('error', 'No tienes permiso para solicitar documentos para este ticket.');
            return;
        }

        $this->validate(['requestDocumentMessage' => $this->rules()['requestDocumentMessage']]);

        try {
            // Envía la notificación al usuario que creó el ticket
            $creator = User::find($this->ticket->createdBy);
            if ($creator) {
                $creator->notify(new RequestMoreDocumentsNotification($this->ticket, $this->requestDocumentMessage));
                session()->flash('message', 'Solicitud de documentos enviada al creador del ticket.');
                $this->requestDocumentMessage = ''; // Limpiar el mensaje
            } else {
                session()->flash('error', 'No se pudo encontrar al creador del ticket para enviar la solicitud.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al enviar la solicitud de documentos: ' . $e->getMessage());
          
        }
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.agent.ticket-details');
    }
}
