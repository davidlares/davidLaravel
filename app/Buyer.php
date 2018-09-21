<?php

namespace App;
use App\User;

class Buyer extends User
{
    // extended for User

    public function transactions(){
      return $this->hasMany('App\Transaction');
    }
}
