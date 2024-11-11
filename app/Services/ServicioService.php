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
    public function storeServicioWithHorarios($data, $userId)
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

            //$this->generateFranjasForServicio($servicio);

            return $servicio;
        } catch (\Exception $e) {
            Toaster::error('Error al crear el servicio' . $e->getMessage());
        }
    }

    public function removeOldServicioHorariosAndUpdate($data, Servicio $servicio)
    {
        try {
            $servicio->update(array_filter([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio'],
                'incio_turno' => $data['incio_turno'] ?? null,
                'fin_turno' => $data['fin_turno'] ?? null,
                'duracion' => $data['duracion'] ?? null,
                'dias_disponible' => $data['dias_disponible'] ?? null
            ], function ($value) {
                return !is_null($value);
            }));
        } catch (Exception $e) {
            Toaster::error('Error al actualizar el servicio');
        }
    }

    public function generateFranjasForServicio($shiftStart, $shiftEnd, $duration)
    {
        $shiftStart = Carbon::createFromTimeString($shiftStart);
        $shiftEnd = Carbon::createFromTimeString($shiftEnd);

        $horarios = [];

        while ($shiftStart->lt($shiftEnd)) {
            $startTime = $shiftStart->toTimeString();
            $endTime = $shiftStart->addMinutes((int)$duration)->toTimeString();

            if ($shiftStart->gt($shiftEnd)) {
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

    public function updateHorarioState(int $id)
    {
        $horario = Horario::firstWhere('id', $id);


        $horario->update([
            'disponibilidad' => 'Reservado'
        ]);
    }

    public function updateFranjaStateOnDelete(int $id)
    {
        $horario = Horario::firstWhere('id', $id);


        $horario->update([
            'disponibilidad' => 'Disponible'
        ]);
    }


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
            Toaster::error('Error al editar la reserva' . $e->getMessage());
            return redirect('/');
        }
    }

    public function DestroyServicio($idServicio)
    {
        try {
            $servicio = Servicio::find($idServicio);

            if (!$servicio) {
                Toaster::error('El servicio no existe o ya fue eliminado');
                return redirect()->back();
            }

            if (optional($servicio->reservas)->whereIn('estado', ['Pendiente', 'Confirmado'])->count() > 0) {
                Toaster::error('No se puede borrar el servicio porque tiene reservas activas');
                return redirect()->back();
            }

            $servicio->delete();
            Toaster::success('Servicio borrado correctamente');
            return redirect()->back();

        } catch (Exception $e) {
            Toaster::error('Error al borrar el servicio: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
