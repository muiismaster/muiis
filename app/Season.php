<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public function crop(){
        return $this->belongsTo('App\Crop');
    }
}
