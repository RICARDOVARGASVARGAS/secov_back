<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Driver;
use App\Models\Example;
use App\Models\Group;
use App\Models\TypeCar;
use App\Models\Year;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate' => $this->faker->numerify('###########'),
            'chassis' => $this->faker->numerify('###########'),
            'motor' => $this->faker->numerify('###########'),
            'file_car' => $this->faker->imageUrl(),
            'brand_id' => Brand::all()->random()->id,
            'type_car_id' => TypeCar::all()->random()->id,
            'group_id' => Group::all()->random()->id,
            'year_id' => Year::all()->random()->id,
            'color_id' => Color::all()->random()->id,
            'example_id' => Example::all()->random()->id,
            'driver_id' => Driver::all()->random()->id,
            'number_soat' => $this->faker->numerify('###########'),
            'file_soat' => $this->faker->imageUrl(),
            'date_soat_issue' => $this->faker->date(),
            'date_soat_expiration' => $this->faker->date(),
            'file_technical_review' => $this->faker->imageUrl(),
        ];
    }
}
