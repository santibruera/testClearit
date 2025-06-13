<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModoTransporte;

class ModoTransporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modos = ['air', 'land', 'sea'];

        foreach ($modos as $modo) {
            ModoTransporte::firstOrCreate(['name' => $modo]);
        }
    }
}
