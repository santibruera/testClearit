<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TipoTicket;
use App\Models\ModoTransporte;
use App\Models\TicketStatus;
use App\Models\Producto;
use App\Models\User;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que los seeders de Roles y Lookups se han ejecutado antes
        // Puedes verificar si existen datos o lanzar una excepción si no
        $tipoTickets = TipoTicket::all();
        $modoTransportes = ModoTransporte::all();
        $ticketStatuses = TicketStatus::all();
        $productos = Producto::all();

        $creatorUser = User::role('user')->first(); 
        $agentUser = User::role('agent')->first();   

        if ($tipoTickets->isEmpty() || $modoTransportes->isEmpty() || $ticketStatuses->isEmpty() || $productos->isEmpty() || !$creatorUser || !$agentUser) {
            $this->command->warn('Faltan datos de referencia (Tipos, Modos, Estados, Productos o Usuarios Agente/Usuario). Asegúrate de ejecutar los seeders de referencia primero.');
            return; 
        }

        for ($i = 0; $i < 10; $i++) {
            Ticket::factory()->create([
                'name' => 'Ticket de prueba_'. rand(1, 100), 
                'typeId' => $tipoTickets->random()->id,
                'modeOfTransport' => $modoTransportes->random()->id, 
                'productId' => $productos->random()->id,
                'status' => $ticketStatuses->random()->id,
                'createdBy' => $creatorUser->id,
                'assignedTo' => $agentUser->id,   
            ]);
        }
        $this->command->info('Tickets de prueba creados exitosamente.');
    }
}
