<?php

namespace App\Livewire\Agent;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class AssignedTicketsList extends Component
{
    public $tickets;

    public function mount()
    {
        // Carga los tickets donde el usuario autenticado es el agente asignado
        $this->tickets = Ticket::where('assignedTo', Auth::id())
                                ->with(['tipoTicket', 'ticketStatus', 'user', 'producto']) // Eager loading (user para creador)
                                ->latest()
                                ->get();
    }

    public function render()
    {
        return view('livewire.agent.assigned-tickets-list');
    }
}
