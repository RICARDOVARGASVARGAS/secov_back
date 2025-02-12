<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Color;
use App\Models\Driver;
use App\Models\Example;
use App\Models\Group;
use App\Models\Inspection;
use App\Models\Insurance;
use App\Models\License;
use App\Models\Permit;
use App\Models\TypeCar;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ejecutar el seeder de usuarios, roles y permisos
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
        // Color::factory(10)->create();
        // Example::factory(10)->create();
        // TypeCar::factory(10)->create();
        // Group::factory(10)->create();
        // Brand::factory(10)->create();
        // Year::factory(10)->create();
        // Driver::factory(20)->create()->each(function ($driver) {
        //     $driver->licenses()->saveMany(License::factory(3)->make());

        //     $driver->cars()->saveMany(Car::factory(3)->make())->each(function ($car) {
        //         $car->inspections()->saveMany(Inspection::factory(3)->make());
        //         $car->insurances()->saveMany(Insurance::factory(3)->make());
        //         $car->permits()->saveMany(Permit::factory(3)->make());
        //     });
        // });
    }
}
