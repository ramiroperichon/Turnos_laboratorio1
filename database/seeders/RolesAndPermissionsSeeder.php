<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use GuzzleHttp\Promise\Create;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $proveedorRole = Role::create(['name' => 'proveedor']);

        $administradorRole = Role::Create(['name' => 'administrador']);

        // Crear permisos
        Permission::create(['name' => 'acceso al dashboard']);
        Permission::create(['name' => 'administrar turnos']);
        Permission::create(['name' => 'permisos del administrador']);

        // Asignar permisos a roles
        $proveedorRole->givePermissionTo('acceso al dashboard');
        $proveedorRole->givePermissionTo('administrar turnos');

        $administradorRole->givePermissionTo('permisos del administrador');

    }
}
