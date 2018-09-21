<?php

namespace App;
use App\User;

class Seller extends User
{
    // extended for User
    public function products(){
      return $this->hasMany('App\Product');
    }
}
