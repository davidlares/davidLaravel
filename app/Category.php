<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $fillable = ['name','description'];
    protected $dates = ['deleted_at'];

    // hiding pivots
    protected $hidden = ['pivot'];

    public function products(){
      return $this->belongsToMany('App\Product');
    }
}
