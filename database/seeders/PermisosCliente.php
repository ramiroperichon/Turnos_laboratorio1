<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class PermisosCliente extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clienteRole = Role::create(['name' => 'cliente']);

        Permission::create(['name' => 'acceso-a-mis-turnos']);
        Permission::create(['name' => 'acceso-a-dashboard-cliente']);

        // Asignar permisos a roles
        $clienteRole->givePermissionTo('acceso-a-mis-turnos');
        $clienteRole->givePermissionTo('acceso-a-dashboard-cliente');

        $user = User::find(2);  // Asumiendo que el usuario con ID 1 ya existe
        $user->assignRole('proveedor');
    }
}
