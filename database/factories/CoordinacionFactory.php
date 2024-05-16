<?php

namespace Database\Factories;

use App\Models\Coordinacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coordinacion>
 */
class CoordinacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Coordinacion::class;
    public function definition(): array
    {
        $coordinacines = ['tecnologia', 'banca', 'contabilidad', 'empresarial'];
        return [
            'name' => $this->faker->randomElement($coordinacines)
        ];
    }
}
