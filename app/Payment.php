<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function farmer(){
        return $this->belongsTo('App\Farmer');
    }

    

    public function subscription(){
        return $this->belongsTo('App\Subscription');
    }
}
