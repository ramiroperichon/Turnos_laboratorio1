<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 5; $i <= 15; $i++) {
            $cliente = Cliente::create([
                'usuario_id' => $i,
                'documento' => $faker->unique()->bothify('########'),
            ]);
        }
    }
}
