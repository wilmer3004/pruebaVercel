<?php

namespace Database\Seeders;

use App\Models\Oferta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $offers = [
            ['name'=>'abierta'],
            ['name'=>'cerrada'],
        ];

        foreach($offers as $offer){
            Oferta::factory()->create($offer);
        }
    }
}
