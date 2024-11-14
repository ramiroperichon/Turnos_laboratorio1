<?php

namespace App\Services\Validators;

use App\Models\Servicio;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class CheckServicioFinSchedule implements ValidationRule, DataAwareRule
{
    protected $data = [];
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nuevoFin = Carbon::createFromTimeString($value);

        $serviciosOutside = Servicio::where('proveedor_id', $this->user_id)->where(function ($query) use ($nuevoFin) {
            $query->whereTime('fin_turno', '>', $nuevoFin->format('H:i:s'));
        })
            ->exists();

        if ($serviciosOutside) {
            $fail('No se puede modificar el horario porque hay servicios programados fuera del nuevo rango horario.');
        }
    }
}
