<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;


class MyTicketsList extends Component
{
    public $tickets;

    public function mount()
    {
        // Carga los tickets donde el usuario autenticado es el creador
        $this->tickets = Ticket::where('createdBy', Auth::id())
                                ->with(['tipoTicket', 'ticketStatus', 'assignedUser', 'producto']) 
                                ->latest() 
                                ->get();
    }
public function createTicket()
    {
        // Redirige a la página de creación de tickets
        return redirect()->route('user.crear-ticket');
    }
    public function render()
    {
        return view('livewire.user.my-tickets-list');
    }
}
