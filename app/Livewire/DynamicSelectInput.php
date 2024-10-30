<?php

namespace App\Livewire;

use App\Models\Horario;
use App\Models\Servicio;
use App\Services\ServicioService;
use Carbon\Carbon;
use Livewire\Component;

class DynamicSelectInput extends Component
{
    protected $servicioService;

    public $horarios = [];
    public $selectedDate;
    public $servicio;
    public $reservas;

    public function mount($servicio)
    {
        $this->servicio = $servicio;
    }

    public function updateHorarios($date)
    {
        $filteredReservas = $this->reservas->where('fecha_reserva', $date);
        $this->horarios = $this->generateFranjasForServicio($this->servicio->incio_turno, $this->servicio->fin_turno, $this->servicio->duracion);

        foreach ($filteredReservas as $reserva) {
            foreach ($this->horarios as $index => $horario) {
                if ($reserva->hora_inicio == $horario->hora_inicio && $reserva->hora_fin == $horario->hora_fin) {
                    $this->horarios[$index]->disponibilidad = 'Reservado';
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.dynamic-select-input');
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

        return $horarios;
    }
}
