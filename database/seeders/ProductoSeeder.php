<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            ['name' => 'Producto A', 'description' => 'Descripción del Producto A'],
            ['name' => 'Producto B', 'description' => 'Descripción del Producto B'],
            ['name' => 'Producto C', 'description' => 'Descripción del Producto C'],
        ];

        foreach ($productos as $producto) {
           Producto::create($producto);
        }
    }
}
