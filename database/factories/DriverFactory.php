<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'document_type' => $this->faker->randomElement(['pasaporte', 'dni']),
            'document_number' => $this->faker->unique()->numerify('###########'),
            'name' => $this->faker->unique()->name(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $this->faker->date(),
            'image' => $this->faker->imageUrl(),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'gender' => $this->faker->randomElement(['masculino', 'femenino']),
            'license_number' => $this->faker->numerify('###########'),
            'license_expiration_date' => $this->faker->date(),
            'license_issue_date' => $this->faker->date(),
            'license_class' => $this->faker->numerify('###########'),
            'license_category' => $this->faker->numerify('###########'),
            'image_license' => $this->faker->imageUrl(),
        ];
    }
}
