<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming the user with email jdpelaez68@gmail.com exists and has id  1
        // Replace the placeholders with actual data
        Persona::create([
            'name' => 'Diego Alejandro',
            'lastname' => 'Boada Morales',
            'document' => '1000730206',
            'email' => 'jdpelaez68@gmail.com',
            'phone' => '1234567890',
            'user_id' =>  1, // ID del usuario existente
            'document_type_id' =>  1, // ID del usuario existente
        ]);
    }
}
