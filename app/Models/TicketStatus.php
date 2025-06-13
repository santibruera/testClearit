<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    /** @use HasFactory<\Database\Factories\TicketStatusFactory> */
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    protected $casts = [
        'name' => 'string'
    ];
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status');
    }

}
