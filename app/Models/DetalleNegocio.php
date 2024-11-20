<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleNegocio extends Model
{
    protected $fillable = ['nombre', 'email', 'telefono', 'direccion', 'latitud', 'logitud', 'Iurl', 'Furl', 'Turl', 'Xurl'];
}
