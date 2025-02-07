<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'Administrador']);

        // Asignar permisos al rol de administrador
        $permissions = Permission::all()->pluck('id')->toArray();
        $admin->permissions()->attach($permissions);
    }
}
