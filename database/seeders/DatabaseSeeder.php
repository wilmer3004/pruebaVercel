<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Competencia;
use App\Models\Condicion;
use App\Models\Contrato;
use App\Models\Coordinacion;
use App\Models\Oferta;
use App\Models\Rol;
use App\Models\TipoComponente;
use App\Models\TipoPrograma;
use App\Models\Trimestre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Rol::factory(3)->create();
        Coordinacion::factory(4)->create();
        Condicion::factory(5)->create();
        Contrato::factory(2)->create();
        TipoPrograma::factory(2)->create();
        TipoComponente::factory(2)->create();
        Trimestre::factory(6)->create();
        Oferta::factory(3)->create();
        // Competencia::factory(4)->create();
    }
}
