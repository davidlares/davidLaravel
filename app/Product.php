<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // status options for DB
    const AVAILABLE = 'available';
    const NOT_AVAILABLE = 'not available';

    protected $table = 'products';
    protected $fillable = ['name','description','quantity','status','image','seller_id'];

    // setting up the transaction option
    public function isAvailable(){
      return $this->status == Product::AVAILABLE;
    }

    public function categories(){
      return $this->belongsToMany('App\Category');
    }

    public function seller(){
      return $this->belongsTo('App\Seller');
    }

    public function transactions(){
      return $this->hasMany('App\Transaction');
    }
}
