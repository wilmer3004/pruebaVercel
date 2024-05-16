<?php

namespace Database\Seeders;

use App\Models\TipoComponente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComponentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //seeder de registro de tipos de componentes

        $componentsTypes = [
            ['name'=>'tecnica'],
            ['name'=>'transversal'],
        ];

        foreach($componentsTypes as $componentType){
            TipoComponente::factory()->create($componentType);
        }
    }
}
