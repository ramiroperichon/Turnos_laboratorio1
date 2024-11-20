<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 5; $i++) {
            $proveedor = Proveedor::create([
                'horario_inicio' => '08:00',
                'horario_fin' => '18:00',
                'usuario_id' => $i,
                'profesion' => $faker->jobTitle,
            ]);
        }
    }
}
