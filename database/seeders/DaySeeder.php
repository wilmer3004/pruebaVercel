<?php

namespace Database\Seeders;

use App\Models\Jornada;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeder de registro de jornadas

        $joranadas = [
            ['name'=>'mañana','color'=> 'rgb(53, 139, 224)'],
            ['name'=>'noche','color'=> 'rgba(240, 56, 10, 0.66)'],
            ['name'=>'tarde','color'=> 'rgba(0, 255, 4, 0.66)'],
            ['name'=>'fin de semana','color'=> 'rgba(198, 21, 193, 0.52)'],
            ['name'=>'madrugada','color'=> 'rgba(255, 234, 0, 0.46)'],
        ];

        //recorrer el arreglo para el registro automatico
        foreach($joranadas as $jornada){
            Jornada::factory()->create($jornada);
        }
    }
}
