<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use File;
class Descarga extends Model
{
    use HasFactory;

    public function sizefile(){
        $file = $this->archivo;
        $size = 0;
        if($file){            
            $size = Storage::size($this->archivo);
            $size = intVal($size/1024);            
        }
        
        return $size;
    }
}
