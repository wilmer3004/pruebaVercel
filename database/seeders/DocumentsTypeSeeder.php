<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $documentTypes = [
            ['name' => 'Cedula de Ciudadania', 'nicknames' => 'C.C'],
            ['name' => 'Tarjeta de Identidad', 'nicknames' => 'T.I']
        ];

        foreach($documentTypes as $documentType){
            TipoDocumento::factory()->create($documentType);
        }

    }
}
