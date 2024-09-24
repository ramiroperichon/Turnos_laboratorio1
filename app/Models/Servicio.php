<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'incio_turno', 'fin_turno', 'duracion', 'dias_disponible', 'habilitado'];

    public function franjasHorarias()
    {
        return $this->hasMany(Horario::class);
    }

    protected $attributes = [
        'habilitado' => true,
    ];
}
