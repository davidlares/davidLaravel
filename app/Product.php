<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    // status options for DB
    const AVAILABLE = 'available';
    const NOT_AVAILABLE = 'not available';

    protected $table = 'products';
    protected $fillable = ['name','description','quantity','status','image','seller_id'];
    protected $dates = ['deleted_at'];

    // hiding pivots
    protected $hidden = ['pivot'];

    // transformers
    public $transformer = ProductTransformer::class;

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
