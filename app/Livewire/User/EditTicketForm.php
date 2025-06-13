<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TipoTicket;
use App\Models\ModoTransporte;
use App\Models\TicketStatus;
use App\Models\Producto;
use App\Models\User;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use App\Livewire\Forms\TicketsForms;

class EditTicketForm extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public TicketsForms $form;

    public $attachments = [];
    public $existingAttachments;

    public $tipoTickets;
    public $modoTransportes;
    public $productos;
    public $ticketStatuses;
    public $agents;
    public $ticketId;
    public $canEditAllFields = false;
    public $canUploadFiles = false;
    public $isClosed = false;

    protected array $rules = [
        'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
    ];

    public function mount(int $ticketId)
    {
        $this->ticket = Ticket::where('id', $ticketId)
            ->where('createdBy', Auth::id())
            ->with(['tipoTicket', 'modoTransporte', 'producto', 'ticketStatus', 'user', 'assignedUser', 'attachments.uploader'])
            ->firstOrFail();
        $this->ticketId = $ticketId;
        $this->form->name = $this->ticket->name;
        $this->form->description = $this->ticket->description;
        $this->form->typeId = $this->ticket->typeId;
        $this->form->modeOfTransport = $this->ticket->modeOfTransport;
        $this->form->productId = $this->ticket->productId;
        $this->form->origin = $this->ticket->origin;
        $this->form->status = $this->ticket->status;
        $this->form->assignedTo = $this->ticket->assignedTo;

        $this->tipoTickets = TipoTicket::all();
        $this->modoTransportes = ModoTransporte::all();
        $this->productos = Producto::all();
        $this->ticketStatuses = TicketStatus::all();
        $this->agents = User::role('agent')->get();

        $this->existingAttachments = $this->ticket->attachments;

        $this->isClosed = ($this->ticket->ticketStatus->name === 'Completed');
        $isInProgress = ($this->ticket->ticketStatus->name === 'In Progress');
        $isNew = ($this->ticket->ticketStatus->name === 'New');

        $this->canEditAllFields = $isNew;
        $this->canUploadFiles = $isNew || $isInProgress;

        if ($this->isClosed) {
            session()->flash('error', 'No puedes editar un ticket que ya está cerrado.');
        }
    }

    public function updateTicket()
    {
        if ($this->isClosed) {
            session()->flash('error', 'No se pueden realizar cambios en un ticket cerrado.');
            return;
        }

        if ($this->canEditAllFields) {
            $this->form->validate();
        }

        if ($this->canUploadFiles && !empty($this->attachments)) {
            $this->validate($this->rules);
        }

        try {
            if ($this->canEditAllFields) {
                $this->ticket->update([
                    'name' => $this->form->name,
                    'description' => $this->form->description,
                    'typeId' => $this->form->typeId,
                    'modeOfTransport' => $this->form->modeOfTransport,
                    'productId' => $this->form->productId,
                    'origin' => $this->form->origin,
                ]);
            }

            if ($this->canUploadFiles && !empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $path = $file->store('attachments', 'public');

                    $this->ticket->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
                $this->attachments = [];
                $this->loadTicket();
            }

            session()->flash('message', 'Ticket actualizado exitosamente!');
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al actualizar el ticket: ' . $e->getMessage());
        }
    }

    public function deleteAttachment(int $attachmentId)
    {
        if ($this->isClosed || Auth::id() !== $this->ticket->createdBy) {
            session()->flash('error', 'No tienes permiso para eliminar este archivo adjunto o el ticket está cerrado.');
            return;
        }

        try {
            $attachment = TicketAttachment::where('id', $attachmentId)
                ->where('ticket_id', $this->ticket->id)
                ->firstOrFail();

            Storage::disk('public')->delete($attachment->file_path);

            $attachment->delete();

            session()->flash('message', 'Archivo adjunto eliminado correctamente.');
            $this->loadTicket();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el archivo adjunto: ' . $e->getMessage());
            \Log::error('Error deleting attachment: ' . $e->getMessage(), ['attachment_id' => $attachmentId, 'user_id' => Auth::id()]);
        }
    }
    protected function loadTicket()
    {
        $this->ticket = Ticket::where('id', $this->ticketId)
                              ->where('createdBy', Auth::id())
                              ->with(['tipoTicket', 'modoTransporte', 'producto', 'ticketStatus', 'user', 'assignedUser', 'attachments.uploader'])
                              ->firstOrFail();
    }
    public function render()
    {
        return view('livewire.user.edit-ticket-form');
    }
}
