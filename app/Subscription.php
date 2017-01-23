<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function payments(){
        return $this->hasMany('App\Payment');
    }

    public function farmer(){
        return $this->belongsTo('App\Farmer');
    }

    public function season(){
        return $this->belongsTo('App\Season');
    }
}
