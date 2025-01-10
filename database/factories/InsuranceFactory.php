<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insurance>
 */
class InsuranceFactory extends Factory
{
    public function definition(): array
    {
        $date = \Carbon\Carbon::parse($this->faker->date());
        return [
            'number_insurance' => $this->faker->numerify('###########'),
            'issue_date' => $date->toDateString(),
            'expiration_date' => $date->copy()->addYear()->toDateString(),
            // 'file_insurance' => $this->faker->imageUrl(),
            // 'car_id' => Car::all()->random()->id
        ];
    }
}
