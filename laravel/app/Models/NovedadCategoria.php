<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadCategoria extends Model
{
    use HasFactory;

    public function obtenerNovedades() {
        return $this->hasMany('App\Models\Novedad','categoria')->orderBy('orden','asc');
    }
}
