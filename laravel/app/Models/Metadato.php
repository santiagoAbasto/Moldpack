<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Metadato extends Model
{
    protected $table = 'metadatos';
    protected $fillable = [
        'id', 'keywords', 'descripcion', 'seccion'
    ];
}
