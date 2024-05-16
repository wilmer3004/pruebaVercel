<?php

namespace Database\Seeders;

use App\Models\Contrato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeder Contracts
        $contracts = [
            ['name'=>'planta','total_hours'=>120],
            ['name'=>'contratista','total_hours'=>160],
        ];
        foreach($contracts as $contract){
            Contrato::factory()->create($contract);
        }
    }
}
