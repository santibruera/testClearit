<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;
    public $fillable = [
        'name',
        'description',
    ];
    public $casts = [
        'name' => 'string',
        'description' => 'string',
    ];
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'productId');
    }
}
