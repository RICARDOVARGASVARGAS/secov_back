<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->colorName(),
            'hex' => $this->faker->unique()->hexColor(),
        ];
    }
}
