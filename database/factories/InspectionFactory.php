<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inspection>
 */
class InspectionFactory extends Factory
{
    public function definition(): array
    {
        $date = \Carbon\Carbon::parse($this->faker->date());
        return [
            'issue_date' => $date->toDateString(),
            'expiration_date' => $date->copy()->addYear()->toDateString(),
            // 'file_inspection' => $this->faker->imageUrl(),
            // 'car_id' => Car::all()->random()->id
        ];
    }
}
