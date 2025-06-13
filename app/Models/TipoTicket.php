<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTicket extends Model
{
    /** @use HasFactory<\Database\Factories\TipoTicketFactory> */
    use HasFactory;
    protected $fillable =[
        'name'
    ];
    protected $casts = [
        'name' => 'string'
    ];
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'type');
    }
    
}
