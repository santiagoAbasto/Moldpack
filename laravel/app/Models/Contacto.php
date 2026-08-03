<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
     protected $fillable = [

        'id', 'direccion', 'celular', 'telefono', 'correo', 'orden'
    ];
}
