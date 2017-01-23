<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    public function subscriptions(){
        return $this->hasMany('App\Subscription');
    }

    public function payments(){
        return $this->hasMany('App\Payment');
    }
}
