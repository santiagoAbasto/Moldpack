<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'id',
        'orden',
        'imagen',
        'imagen',
        'descripcion',
    ];
}
