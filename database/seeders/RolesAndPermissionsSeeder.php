<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'cliente']);
        Role::create(['name' => 'proveedor']);
        Role::create(['name' => 'administrador']);

        $administrador = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);

        $administrador->assignRole('administrador');
    }
}
