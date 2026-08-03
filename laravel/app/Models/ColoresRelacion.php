<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ColoresRelacion extends Model
{
    protected $fillable = [
        'id','producto_id','relacion_id'
    ];

    use HasFactory;

    public function producto(){
        return $this->belongsTo('App\Models\Color','relacion_id');
    }
    public function colores(){
        return $this->belongsTo('App\Models\Color','relacion_id');
    }
}
