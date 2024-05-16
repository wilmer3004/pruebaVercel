<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Oferta>
 */
class OfertaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $oferta = ['cerrado', 'abierta', 'articulacion'];
        return [
            'name' => $this->faker->randomElement($oferta)
        ];
    }
}
