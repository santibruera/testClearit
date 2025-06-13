<?php
namespace App\Livewire\Forms;
use Livewire\Form;
use Illuminate\Validation\Rule;

class TicketsForms extends Form
{
    public $name = '';
    public $description = '';
    public $typeId = '';
    public $modeOfTransport = '';
    public $productId = '';
    public $origin = '';
    public $status = '';
    public $assignedTo = '';

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'typeId' => ['required', Rule::exists('tipo_tickets', 'id')],
            'modeOfTransport' => ['required', Rule::exists('modo_transportes', 'id')],
            'productId' => ['required', Rule::exists('productos', 'id')],
            'origin' => 'nullable|string|max:255',
            'status' => ['required', Rule::exists('ticket_statuses', 'id')],
            'assignedTo' => ['nullable', Rule::exists('users', 'id')],
        ];
    }

    public function setTicketDefaults($statusId = null)
    {
        if ($statusId) {
            $this->status = $statusId;
        }
    }
}
