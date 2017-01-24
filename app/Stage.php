<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    //

    public function crop(){
        return $this->belongsTo('App\Crop');
    }

    public function agronomicMessage(){
        return $this->hasOne('App\AgronomicMessage');
    }
}
