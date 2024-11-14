<?php

namespace App\Services\Validators;

use App\Models\Servicio;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class CheckAvailableDays implements ValidationRule, DataAwareRule
{
    protected $data = [];
    protected $userId;
    protected $idServicio;
    protected $isFilament;

    public function __construct($userId, $idServicio = null, $isFilament = false)
    {
        $this->userId = $userId;
        $this->idServicio = $idServicio;
        $this->isFilament = $isFilament;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isFilament) {
            $inicio = \Carbon\Carbon::createFromFormat('H:i', $this->data['mountedTableActionsData'][0]['incio_turno']);
            $fin = \Carbon\Carbon::createFromFormat('H:i', $this->data['mountedTableActionsData'][0]['fin_turno']);
        } else {
            $inicio = \Carbon\Carbon::createFromFormat('H:i', $this->data['incio_turno']);
            $fin = \Carbon\Carbon::createFromFormat('H:i', $this->data['fin_turno']);
        }
        $service = Servicio::where(function ($query) {
            if ($this->isFilament) {
                foreach ($this->data['mountedTableActionsData'][0]['dias_disponible'] as $day) {
                    $query->orWhere('dias_disponible', 'LIKE', '%' . $day . '%');
                }
            } else {
                foreach ($this->data['dias_disponible'] as $day) {
                    $query->orWhere('dias_disponible', 'LIKE', '%' . $day . '%');
                }
            }
        })
            ->whereIn('proveedor_id', [$this->userId])
            ->get();

        if ($service->count() != 0) {
            foreach ($service as $s) {
                if ($this->idServicio != $s->id) {
                    $hora_inicio = \Carbon\Carbon::createFromFormat('H:i:s', $s->incio_turno);
                    $hora_fin = \Carbon\Carbon::createFromFormat('H:i:s', $s->fin_turno);
                    if (
                        $inicio->between($hora_inicio, $hora_fin, false) ||
                        $fin->between($hora_inicio, $hora_fin, false) ||
                        ($inicio->lessThanOrEqualTo($hora_inicio) && $fin->greaterThanOrEqualTo($hora_fin))
                    ) {
                        $fail('No hay horarios disponibles para este servicio en los dias seleccionados.');
                    }
                }
            }
        }
    }
}
