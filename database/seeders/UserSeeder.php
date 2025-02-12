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
            'document' => '70234397',
            'name' => 'Mario',
            'first_name' => 'Ferro',
            'last_name' => 'Guerreros',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('secov2025'),
            'is_active' => true,
            'visible' => false
        ]);

        $user->roles()->attach(1);
    }
}
