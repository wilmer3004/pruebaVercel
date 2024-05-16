<?php

namespace Database\Seeders;

use App\Models\Trimestre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //seeder de registro de trimestres
        $trimestres = [
            ['name'=>'I'],
            ['name'=>'II'],
            ['name'=>'III'],
            ['name'=>'IV'],
            ['name'=>'V'],
            ['name'=>'VI'],
            ['name'=>'VII'],
        ];
        foreach($trimestres as $trimestre){
            Trimestre::factory()->create($trimestre);
        }


    }
}
