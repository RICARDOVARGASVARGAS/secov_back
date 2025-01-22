<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 4 usuarios
        $users = [];
        for ($i = 1; $i <= 4; $i++) {
            $users[] = User::create([
                'document' => '1234567' . $i,
                'name' => 'Nombre ' . $i,
                'first_name' => 'Apellido P ' . $i,
                'last_name' => 'Apellido M' . $i,
                'email' => 'email' . $i . '@example.com',
                'phone_number' => '123456789' . $i,
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);
        }

        // Crear 3 roles
        $roles = [];
        $roles[] = Role::create(['name_es' => 'Administrador', 'name_en' => 'Admin']);
        $roles[] = Role::create(['name_es' => 'Editor', 'name_en' => 'Editor']);
        $roles[] = Role::create(['name_es' => 'Usuario', 'name_en' => 'User']);

        // Crear 20 permisos
        $permissions = [];
        for ($i = 1; $i <= 20; $i++) {
            $permissions[] = Permission::create([
                'name_es' => 'Permiso ' . $i . ' ES',
                'name_en' => 'Permission ' . $i . ' EN',
            ]);
        }

        // Convertir $permissions y $roles en colecciones
        $permissions = collect($permissions);
        $roles = collect($roles);

        // Asignar permisos a los roles
        // Rol 1: Todos los permisos
        $roles[0]->permissions()->sync($permissions->pluck('id')->toArray());

        // Rol 2: La mitad de los permisos
        $roles[1]->permissions()->sync($permissions->take(10)->pluck('id')->toArray());

        // Rol 3: 2 permisos aleatorios
        $roles[2]->permissions()->sync($permissions->random(2)->pluck('id')->toArray());

        // Asignar roles a los usuarios de manera específica
        foreach ($users as $index => $user) {
            if ($index === 0) {
                // El primer usuario (administrador) obtiene todos los roles
                $user->roles()->sync($roles->pluck('id')->toArray());
            } else {
                // Los otros usuarios obtienen un solo rol aleatorio
                $user->roles()->sync($roles->random(1)->pluck('id')->toArray());
            }
        }
    }
}
