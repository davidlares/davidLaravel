<?php

namespace App;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use softDeletes;

    protected $table = 'transactions';
    protected $fillable = ['quantity','buyer_id','product_id'];
    protected $dates = ['deleted_at'];

    // transformers
    public $transformer = TransactionTransformer::class;

    public function buyer(){
      return $this->belongsTo('App\Buyer');
    }

    public function product(){
      return $this->belongsTo('App\Product');
    }
}
