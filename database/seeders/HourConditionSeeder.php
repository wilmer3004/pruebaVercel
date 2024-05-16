<?php

namespace Database\Seeders;

use App\Models\CondicionHora;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HourConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hourConditions = [
            [
                'contract_id' => 1,
                'condition_id' => 2,
                'percentage' => 50
            ],

            [
                'contract_id' => 2,
                'condition_id' => 1,
                'percentage' => 50
            ],

            [
                'contract_id' => 2,
                'condition_id' => 2,
                'percentage' => 30
            ],
            [
                'contract_id' => 1,
                'condition_id' => 1,
                'percentage' => 60
            ],
        ];
        foreach ($hourConditions as $hourCondition) {
            CondicionHora::create($hourCondition);
        }
    }
}
