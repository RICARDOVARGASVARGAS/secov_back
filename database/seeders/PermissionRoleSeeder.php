<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Conductores
            [
                'driver.index',
                'Listar Conductores',
            ],
            [
                'driver.store',
                'Registrar Conductores',
            ],
            [
                'driver.show',
                'Detalle de Conductores',
            ],
            [
                'driver.delete',
                'Eliminar Conductores',
            ],
            [
                'driver.update',
                'Actualizar Conductores',
            ],
            // Licencias
            [
                'license.index',
                'Listar Licencias',
            ],
            [
                'license.store',
                'Registrar Licencias',
            ],
            [
                'license.show',
                'Actualizar Licencias',
            ],
            [
                'license.update',
                'Actualizar Licencias',
            ],
            [
                'license.delete',
                'Eliminar Licencias',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name_en' => $permission[0],
                'name_es' => $permission[1],
            ]);
        }

        // Roles

        $roles = [
            [
                'name_en' => 'admin',
                'name_es' => 'Administrador',
            ],
            [
                'name_en' => 'test',
                'name_es' => 'prueba',
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Asignar permisos a roles

        $admin = Role::where('name_en', 'admin')->first();
        $test = Role::where('name_en', 'test')->first();


        foreach ($permissions as $permission) {
            $admin->permissions()->attach($permission[0]);
        }
    }
}
