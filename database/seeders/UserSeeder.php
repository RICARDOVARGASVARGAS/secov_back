<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'document' => '12345678',
            'name' => 'Admin',
            'first_name' => 'Secov',
            'last_name' => 'Smith',
            'email' => 'admin@gmail.com',
            'phone_number' => '1234567890',
            'password' => Hash::make('password'),
            'is_active' => true,
            'visible' => false
        ]);

        $user->roles()->attach(1);
    }
}
