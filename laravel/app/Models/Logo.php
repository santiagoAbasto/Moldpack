<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logo extends Model
{
    protected $fillable = [
        'id', 'imagen', 'seccion'
    ];
}
