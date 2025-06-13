<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'New'],
            ['name' => 'In Progress'],
            ['name' => 'Completed']
        ];
        foreach ($statuses as $status) {
           TicketStatus::create($status);
        }
    }
}
