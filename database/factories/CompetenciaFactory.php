<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competencia>
 */
class CompetenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        $componente = [1];
        return [
            'number' => $this->faker->randomNumber(),
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            // 'component_id' => $this->faker->randomElement($componente)
        ];
    }
}
