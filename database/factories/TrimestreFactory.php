<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trimestre>
 */
class TrimestreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trimestre = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        // $fase = ['1', '2', '2', '3', '4', '5', '6'];
        return [
            'name' => $this->faker->randomElement($trimestre),
            // 'phase' =>$this->faker->randomElement($fase)
        ];
    }
}
