<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    public function codigo(){
        $id_video = explode('=',$this->link);
        
        $id= null;
        if(isset($id_video[1])){
            $id = $id_video[1];
        }
        return $id;
    }
}
