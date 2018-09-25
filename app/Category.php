<?php

namespace App;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    // transformers
    public $transformer = CategoryTransformer::class;

    protected $table = 'categories';
    protected $fillable = ['name','description'];
    protected $dates = ['deleted_at'];

    // hiding pivots
    protected $hidden = ['pivot'];

    public function products(){
      return $this->belongsToMany('App\Product');
    }
}
