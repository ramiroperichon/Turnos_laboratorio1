<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($user_id = 1; $user_id <= 5; $user_id++) {

            Servicio::create([
                'nombre' => $faker->word,
                'descripcion' => $faker->sentence,
                'precio' => $faker->randomFloat(2, 50, 500),
                'incio_turno' => '08:00',
                'fin_turno' => '18:00',
                'duracion' => 30,
                'dias_disponible' => 'Lunes, Martes, Miercoles, Jueves, Viernes, Sabado',
                'proveedor_id' => $user_id
            ]);
        }
    }
}
