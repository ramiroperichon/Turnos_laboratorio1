<?php

namespace App\Services;

use App\Models\Servicio;
use App\Models\Horario;
use App\Models\Reserva;
use Carbon\Carbon;

class ServicioService
{
    public function storeServicioWithHorarios($data) //Crea el servicio y llama el metodo para generar horarios
    {
        $servicio = Servicio::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'incio_turno' => $data['incio_turno'],
            'fin_turno' => $data['fin_turno'],
            'duracion' => $data['duracion'],
            'dias_disponible' => $data['dias_disponible']
        ]);

        //$this->generateFranjasForServicio($servicio); //llama al metodo para generar horarios

        return $servicio; //devuelve el servicio creado
    }

    public function removeOldServicioHorariosAndUpdate($data, Servicio $servicio) //elimina y vuelve a generar horarios al actualizar el servicio las reservas del servicio son borradas
    {
        //Horario::where('servicio_id', '=', $servicio->id)->delete();

        $servicio->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'incio_turno' => $data['incio_turno'],
            'fin_turno' => $data['fin_turno'],
            'duracion' => $data['duracion'],
            'dias_disponible' => $data['dias_disponible']
        ]);
        //$this->generateFranjasForServicio($servicio);
    }

    private function generateFranjasForServicio(Servicio $servicio) //Generador de horarios por servicio
    {
        $shiftStart = Carbon::createFromTimeString($servicio->incio_turno);
        $shiftEnd = Carbon::createFromTimeString($servicio->fin_turno);
        $duration = $servicio->duracion;


        while ($shiftStart->lt($shiftEnd)) { //mientras el turno de inicio es menor al final del turno
            $startTime = $shiftStart->toTimeString();
            $endTime = $shiftStart->addMinutes((int)$duration)->toTimeString();

            if ($shiftStart->gt($shiftEnd)) { //si el inicio del turno es mayor al final del turno sale del loop
                break;
            }

            Horario::create([
                'servicio_id' => $servicio->id,
                'hora_inicio' => $startTime,
                'hora_fin' => $endTime,
            ]);
        }
    }

    public function updateHorarioState(int $id) //Actualiza el horario al ser reservado
    {
        $horario = Horario::firstWhere('id', $id);


        $horario->update([
            'disponibilidad' => 'Reservado'
        ]);
    }

    public function updateFranjaStateOnDelete(int $id) //Actualiza el horario al ser borrado o cancelado el turno
    {
        $horario = Horario::firstWhere('id', $id);


        $horario->update([
            'disponibilidad' => 'Disponible'
        ]);
    }

    //cargar calendario
    // In your controller method
    public function showCalendar()
    {
        // Assuming you have an Event model
        $events = Reserva::all();

        $eventData = [];

        foreach ($events as $event) {
            $eventData[] = [
                'title' => $event->user->name. '' . $event->servicio->nombre,
                'start' => $event->fecha_reserva.''.$event->franjaHoraria->hora_inicio,
                'end'   => $event->fecha_reserva.''.$event->franjaHoraria->hora_fin,
            ];
        }

        dd($event);

        // Pass the JSON data to the view
        return view('calendar', ['events' => json_encode($eventData)]);
    }
}
