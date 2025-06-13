<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TipoTicket;
use App\Models\ModoTransporte;
use App\Models\TicketStatus;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\TicketsForms; 
use App\Models\TicketAttachment;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Notifications\TicketAssignedNotification;

class CreateTicketForm extends Component
{
   use WithFileUploads;
    public TicketsForms $form; 

    
    public $tipoTickets;
    public $modoTransportes;
    public $productos;
    public $ticketStatuses;
    public $agents;
    public $attachments = []; 
  protected array $rules = [
        'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120', // Máximo 5MB por archivo, tipos comunes
    ];

    public function mount()
    {
        $this->tipoTickets = TipoTicket::all();
        $this->modoTransportes = ModoTransporte::all();
        $this->productos = Producto::all();
        $this->ticketStatuses = TicketStatus::all();
        $this->agents = User::role('agent')->get();

       
        $defaultStatus = TicketStatus::where('name', 'New')->first();
        if ($defaultStatus) {
            $this->form->status = $defaultStatus->id;
        }
    }

    /**
     * Guarda el nuevo ticket en la base de datos.
     */
    public function saveTicket()
    {
        // La validación ahora se hace en el Form Object
        $this->form->validate();

        try {
            $agent = User::role('agent')
                    ->withCount(['assignedTickets'])
                    ->orderBy('assigned_tickets_count', 'asc')
                    ->first();
             $ticket = Ticket::create([
                'name' => $this->form->name,
                'description' => $this->form->description,
                'typeId' => $this->form->typeId,
                'modeOfTransport' => $this->form->modeOfTransport,
                'productId' => $this->form->productId,
                'origin' => $this->form->origin,
                'status' => $this->form->status,
                'createdBy' => Auth::id(),
                'assignedTo' => $agent ? $agent->id : null
            ]);
            foreach ($this->attachments as $file) {
                // Guarda el archivo en la carpeta 'attachments' dentro de 'storage/app/public'
                $path = $file->store('attachments', 'public'); // 'public' es el disco configurado

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_name' => $file->getClientOriginalName(), // Nombre original del archivo
                    'file_path' => $path, // Ruta interna donde se guardó
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
            session()->flash('message', 'Ticket creado exitosamente!');
            $this->form->reset();
            $defaultStatus = TicketStatus::where('name', 'New')->first();
            if ($defaultStatus) {
                $this->form->status = $defaultStatus->id;
            }
            if ($ticket->assignedTo) { // Solo si hay un agente asignado
                $agent = User::find($ticket->assignedTo);
                if ($agent) {
                    $agent->notify(new TicketAssignedNotification($ticket));
                }
            }

            return redirect()->route('user.my-tickets');

        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al crear el ticket: ' . $e->getMessage());
            
           
        }
    }


    public function render()
    {
        return view('livewire.user.create-ticket-form');
    }
}
