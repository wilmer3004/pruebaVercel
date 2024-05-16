<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoPrograma>
 */
class TipoProgramaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipo = ['tecnologo', 'tecnico'];
        return [
            'name' => $this->faker->randomElement($tipo)
        ];
    }
}
