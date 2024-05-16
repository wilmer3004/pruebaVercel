<?php

namespace Database\Seeders;

use App\Models\TipoPrograma;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeder programType
        $programsTypes = [
            ['name'=>'tecnologo'],
            ['name'=>'tecnico'],
        ];
        foreach($programsTypes as $programType){
            TipoPrograma::factory()->create($programType);
        }
    }
}
