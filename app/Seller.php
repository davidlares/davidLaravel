<?php

namespace App;
use App\User;
use App\Transformers\SellerTransformer;
use App\Scopes\SellerScope;

class Seller extends User
{
  protected static function boot(){
    // model base
      // laravel required framework functionality
      parent::boot();
      // agregando el Scope
      static::addGlobalScope(new SellerScope);
  }

    // transformers
    public $transformer = SellerTransformer::class;

    // extended for User
    public function products(){
      return $this->hasMany('App\Product');
    }
}
