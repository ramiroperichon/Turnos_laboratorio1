<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservasTableSeeder extends Seeder
{

    public function run(): void
    {
        $faker = \Faker\Factory::create();


        $servicios = Servicio::all();

        foreach ($servicios as $servicio) {
            for ($i = 0; $i < 2; $i++) {
                $cliente_id = $faker->numberBetween(5, 15);

                $startHour = $faker->numberBetween(8, 17);
                $startMinute = $faker->randomElement([0, 30]);
                $hora_inicio = sprintf('%02d:%02d', $startHour, $startMinute);


                $hora_fin = date('H:i', strtotime($hora_inicio) + 30 * 60);

                Reserva::create([
                    'cliente_id' => $cliente_id,
                    'servicio_id' => $servicio->id,
                    'estado' => $faker->randomElement(['Confirmado', 'Cancelado', 'Pendiente', 'Completado']),
                    'fecha_reserva' => $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
                    'hora_inicio' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                ]);
            }
        }
    }
}
