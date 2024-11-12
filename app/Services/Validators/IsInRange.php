<?php

namespace App\Services\Validators;

use App\Models\Servicio;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class IsInRange implements ValidationRule, DataAwareRule
{
    protected $data = [];
    protected $usuario_inicio;
    protected $usuario_fin;

    public function __construct($usuario_inicio, $usuario_fin)
    {
        $this->usuario_inicio = $usuario_inicio;
        $this->usuario_fin = $usuario_fin;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $usuario_inicio = \Carbon\Carbon::createFromFormat('H:i:s', $this->usuario_inicio);
        $usuario_fin = \Carbon\Carbon::createFromFormat('H:i:s', $this->usuario_fin);
        $hora_inicio = \Carbon\Carbon::createFromFormat('H:i', $this->data['incio_turno']);
        $hora_fin = \Carbon\Carbon::createFromFormat('H:i', $this->data['fin_turno']);

        if (
            !$hora_inicio->between($usuario_inicio, $usuario_fin) ||
            !$hora_fin->between($usuario_inicio, $usuario_fin)
        ) {
            $fail('El turno no está dentro de los horarios disponibles.');
        }
    }
}
