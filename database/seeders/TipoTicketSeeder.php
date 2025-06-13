<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoTicket;
class TipoTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = ['Tipo 1', 'Tipo 2', 'Tipo 3'];

        foreach ($tipos as $tipo) {
            TipoTicket::firstOrCreate(['name' => $tipo]);
        }
    }
}
