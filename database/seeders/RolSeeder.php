<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use the RolFactory to create the specific roles
        Rol::factory()->create(['name' => 'superadmin', 'description' => 'superadministrador']);
        Rol::factory()->create(['name' => 'administrador', 'description' => 'rol de administrador']);
        Rol::factory()->create(['name' => 'programador', 'description' => 'rol de programador']);
        Rol::factory()->create(['name' => 'instructor', 'description' => 'rol de instructor']);
        // Add any other roles you need here
    }
}
