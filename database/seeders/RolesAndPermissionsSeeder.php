<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'agent']);

        $usrUser=User::firstOrCreate(
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password')
            ]
            );
        $usrUser->assignRole('user');
        $usrAgent=User::firstOrCreate(
            [
                'name' => 'Agent',
                'email' => 'agent@gmail.com',
                'password' => Hash::make('password')
            ]
            );    
        $usrAgent->assignRole('agent');
    }
}
