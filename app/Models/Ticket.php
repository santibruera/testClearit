<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoTicket;
use App\Models\ModoTransporte;
use App\Models\TicketStatus;
use App\Models\Producto;
use App\Models\User;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'typeId',
        'modeOfTransport',
        'productId',
        'origin',
        'status',
        'createdBy',
        'assignedTo',
    ];
    protected $casts = [
        'typeId' => 'integer',
        'modeOfTransport' => 'integer',
        'productId' => 'integer',
        'origin' => 'string',
        'status' => 'integer',
        'createdBy' => 'integer',
        'assignedTo' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tipoTicket()
    {
        return $this->belongsTo(TipoTicket::class, 'typeId');
    }
    public function modoTransporte()
    {
        return $this->belongsTo(ModoTransporte::class, 'modeOfTransport');
    }
    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'status');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productId');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assignedTo');
    }
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }
}
