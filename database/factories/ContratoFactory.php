<?php

namespace Database\Factories;

use App\Models\Contrato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contrato>
 */
class ContratoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Contrato::class;
    public function definition(): array
    {
        $contratos = ['planta', 'contratista'];
        return [
            'name' => $this->faker->randomElement($contratos)
        ];
    }
}
