<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rol>
 */
class RolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Rol::class;

    public function definition(): array
    {
        $roles = ['superadmin', 'administrador', 'instructor'];
        return [
            'name' => $this->faker->randomElement($roles),
            'description' => $this->faker->sentence(),
        ];
    }
}
