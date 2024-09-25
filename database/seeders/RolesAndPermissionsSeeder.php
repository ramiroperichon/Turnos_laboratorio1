<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $proveedorRole = Role::create(['name' => 'proveedor']);
        $clienteRole = Role::create(['name' => 'cliente']);

        // Crear permisos
        Permission::create(['name' => 'acceso al dashboard']);
        Permission::create(['name' => 'administrar turnos']);

        // Asignar permisos a roles
        $proveedorRole->givePermissionTo('acceso al dashboard');
        $proveedorRole->givePermissionTo('administrar turnos');

        // Asignar rol a un usuario existente
        $user = User::find(1);  // Asumiendo que el usuario con ID 1 ya existe
        $user->assignRole('proveedor');
    }
}
