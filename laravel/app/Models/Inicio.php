<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inicio extends Model
{
    use HasFactory;

    public function obtenerGaleria(){
        $galeria = $this->galeria;
        $galeria = explode(',',$galeria);
        return $galeria;
    }
    
}
