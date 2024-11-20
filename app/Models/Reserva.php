<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'estado',
        'fecha_reserva',
        'hora_inicio',
        'hora_fin',
        'observaciones',
        'habilitado'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    protected $attributes = [
        'estado' => 'Pendiente',
        'habilitado' => true,
    ];
}
