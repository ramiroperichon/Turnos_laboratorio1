<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = ['servicio_id', 'hora_inicio', 'hora_fin', 'disponibilidad'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    protected $attributes = [
        'disponibilidad' => 'Disponible',
    ];
}
