<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'descripcion', 'imagen', 'orden'
    ];

    public function codigo(){
        $id_video = explode('=',$this->video);
        
        $id= null;
        if(isset($id_video[1])){
            $id = $id_video[1];
        }
        return $id;
    }
}
