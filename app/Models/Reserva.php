<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['cliente_id', 'servicio_id', 'horario_id', 'estado', 'fecha_reserva'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function franjaHoraria()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    protected $attributes = [
        'estado' => 'Pendiente',
    ];
};
