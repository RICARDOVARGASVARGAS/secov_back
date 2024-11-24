<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Color;
use App\Models\Driver;
use App\Models\Example;
use App\Models\Group;
use App\Models\TypeCar;
use App\Models\Year;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Color::factory(10)->create();
        Example::factory(10)->create();
        TypeCar::factory(10)->create();
        Group::factory(10)->create();
        Brand::factory(10)->create();
        Year::factory(10)->create();
        Driver::factory(20)->create();
        Car::factory(30)->create();
    }
}
