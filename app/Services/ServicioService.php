<?php

namespace App\Services;

use App\Models\Servicio;
use App\Models\Horario;
use App\Models\Reserva;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudMaileable;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Masmerise\Toaster\Toaster;

class ServicioService
{
    public function storeServicioWithHorarios($data, $userId) //Crea el servicio y llama el metodo para generar horarios
    {
        try {
            $servicio = Servicio::create([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio'],
                'incio_turno' => $data['incio_turno'],
                'fin_turno' => $data['fin_turno'],
                'duracion' => $data['duracion'],
                'dias_disponible' => $data['dias_disponible'],
                'proveedor_id' => $userId
            ]);

            //$this->generateFranjasForServicio($servicio); //llama al metodo para generar horarios

            return $servicio;
        } catch (\Exception $e) {
            Toaster::error('Error al crear el servicio' . $e->getMessage());
        }
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

    public function generateFranjasForServicio($shiftStart, $shiftEnd, $duration) //Generador de horarios por servicio
    {
        $shiftStart = Carbon::createFromTimeString($shiftStart);
        $shiftEnd = Carbon::createFromTimeString($shiftEnd);

        $horarios = [];

        while ($shiftStart->lt($shiftEnd)) { //mientras el turno de inicio es menor al final del turno
            $startTime = $shiftStart->toTimeString();
            $endTime = $shiftStart->addMinutes((int)$duration)->toTimeString();

            if ($shiftStart->gt($shiftEnd)) { //si el inicio del turno es mayor al final del turno sale del loop
                break;
            }

            $horarios[] = new Horario([
                'hora_inicio' => $startTime,
                'hora_fin' => $endTime,
            ]);
        }

        dd($horarios);
        return $horarios;
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
    public function showCalendar()
    {
        $events = Reserva::all();

        $eventData = [];

        foreach ($events as $event) {
            $eventData[] = [
                'title' => $event->user->name . '' . $event->servicio->nombre,
                'start' => $event->fecha_reserva . '' . $event->hora_inicio,
                'end'   => $event->fecha_reserva . '' . $event->hora_fin,
            ];
        }

        dd($event);

        return view('calendar', ['events' => json_encode($eventData)]);
    }

    public function getAvialableHours($userId, $days, $inicio, $fin, $idServicio = null)
    {
        $inicio = \Carbon\Carbon::createFromFormat('H:i', $inicio);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $fin);

        $service = Servicio::where(function ($query) use ($days) {
            foreach ($days as $day) {
                $query->orWhere('dias_disponible', 'LIKE', '%' . $day . '%');
            }
        })
            ->whereIn('proveedor_id', [$userId])
            ->get();

        if ($service->count() == 0) {
            return true;
        }
        foreach ($service as $s) {
            if ($idServicio != $s->id) {
                $hora_inicio = \Carbon\Carbon::createFromFormat('H:i:s', $s->incio_turno);
                $hora_fin = \Carbon\Carbon::createFromFormat('H:i:s', $s->fin_turno);
                if (
                    $inicio->between($hora_inicio, $hora_fin) ||
                    $fin->between($hora_inicio, $hora_fin) ||
                    ($inicio->lessThanOrEqualTo($hora_inicio) && $fin->greaterThanOrEqualTo($hora_fin))
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    public function IsInRange($usuario_inicio, $usuario_fin, $hora_inicio, $hora_fin)
    {
        $usuario_inicio = \Carbon\Carbon::createFromFormat('H:i:s', $usuario_inicio);
        $usuario_fin = \Carbon\Carbon::createFromFormat('H:i:s', $usuario_fin);
        $hora_inicio = \Carbon\Carbon::createFromFormat('H:i', $hora_inicio);
        $hora_fin = \Carbon\Carbon::createFromFormat('H:i', $hora_fin);


        return $hora_inicio->between($usuario_inicio, $usuario_fin) &&
            $hora_fin->between($usuario_inicio, $usuario_fin);
    }


    public function UpdateReserva($reserva, $estado)
    {

        try {

            $reserva->update([
                'estado' => $estado
            ]);

            if ($estado != 'Completado') {
                Mail::to($reserva->user->email)
                    ->send(new SolicitudMaileable(
                        $reserva->hora_inicio,
                        $reserva->hora_fin,
                        $reserva->fecha_reserva,
                        $reserva->estado,
                        $reserva->user->name,
                        $reserva->servicio->precio
                    ));
            }
        } catch (Exception $e) {
            Toaster::error('Error al editar la reserva');
            return redirect('/')->withErrors($e)->withInput();
        }
    }
}
