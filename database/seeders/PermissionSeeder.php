<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Marcas - Brand
            ['Listar Marcas', 'brand.index'],
            ['Crear Marcas', 'brand.create'],
            ['Editar Marcas', 'brand.edit'],
            ['Eliminar Marcas', 'brand.destroy'],

            // Años - Year
            ['Listar Años', 'year.index'],
            ['Crear Años', 'year.create'],
            ['Editar Años', 'year.edit'],
            ['Eliminar Años', 'year.destroy'],

            // Modelos - Example
            ['Listar Modelos', 'example.index'],
            ['Crear Modelos', 'example.create'],
            ['Editar Modelos', 'example.edit'],
            ['Eliminar Modelos', 'example.destroy'],

            // Tipo de Vehículos - Type
            ['Listar Tipos', 'type.index'],
            ['Crear Tipos', 'type.create'],
            ['Editar Tipos', 'type.edit'],
            ['Eliminar Tipos', 'type.destroy'],

            // Colores - Color
            ['Listar Colores', 'color.index'],
            ['Crear Colores', 'color.create'],
            ['Editar Colores', 'color.edit'],
            ['Eliminar Colores', 'color.destroy'],

            // Asociaciones - Group
            ['Listar Asociaciones', 'group.index'],
            ['Crear Asociaciones', 'group.create'],
            ['Editar Asociaciones', 'group.edit'],
            ['Eliminar Asociaciones', 'group.destroy'],

            // Conductores - Driver
            ['Listar Conductores', 'driver.index'],
            ['Crear Conductores', 'driver.create'],
            ['Editar Conductores', 'driver.edit'],
            ['Eliminar Conductores', 'driver.destroy'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name_es' => $permission[0],
                'name_en' => $permission[1],
            ]);
        }
    }
}
