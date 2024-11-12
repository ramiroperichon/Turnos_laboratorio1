<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;  
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {

        $proveedorRole = Role::create(['name' => 'proveedor']);
        $administradorRole = Role::create(['name' => 'administrador']);


        Permission::create(['name' => 'acceso al dashboard']);
        Permission::create(['name' => 'administrar turnos']);
        Permission::create(['name' => 'permisos del administrador']);


        $proveedorRole->givePermissionTo('acceso al dashboard');
        $proveedorRole->givePermissionTo('administrar turnos');
        $administradorRole->givePermissionTo('permisos del administrador');


        $administrador = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);

        $administrador->assignRole('administrador');
    }
}
