<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\License>
 */
class LicenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numerify('###########'),
            'class' => $this->faker->word(),
            'category' => $this->faker->word(),
            'issue_date' => $this->faker->date(),
            'renewal_date' => $this->faker->date(),
            // 'file' => $this->faker->imageUrl(),
            // 'driver_id' => Driver::all()->random()->id
        ];
    }
}
