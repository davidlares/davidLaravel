<?php

namespace App;

use App\User;
use App\Transformers\BuyerTransformer;
use App\Scopes\BuyerScope;

class Buyer extends User
{
    // transformers
    public $transformer = BuyerTransformer::class;

    // extended for User
    // constructor
    protected static function boot(){

        // model base
        // laravel required framework functionality
        parent::boot();
        // agregando el Scope
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(){
      return $this->hasMany('App\Transaction');
    }
}
