<?php

namespace Database\Seeders;

use App\Models\Condicion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CondiTionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeder de registro de condiciones
        $conditions=[
            ['name'=>'senova','description'=> 'sennova instructor'],
            ['name'=>'desarrollo curricular','description'=> 'desarrollo curricular instructor'],
        ];

        //recorrer arreglo para el registro automatico
        foreach ($conditions as $condition) {
             Condicion::factory()->create($condition);
        }
    }
}
