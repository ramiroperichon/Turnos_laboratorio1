<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'incio_turno',
        'fin_turno',
        'duracion',
        'dias_disponible',
        'habilitado',
        'proveedor_id',
        'observaciones'
    ];

    protected $attributes = [
        'habilitado' => true,
    ];

    public function proveedor()
    {
        return $this->belongsTo(User::class, 'proveedor_id');
    }
}
