<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    public function seasons(){
        return $this->hasMany('App\Season');
    }

    public function stage(){
        return $this->hasOne('App\Stage');
    }
}
