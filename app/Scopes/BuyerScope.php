<?php
  // global scope -> global query for each model everytime its used

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// interface
class BuyerScope implements Scope {
    // executed when the Scope its called
    public function apply(Builder $builder, Model $model){
      // for each query, set "has('transactions')"
      $builder->has('transactions');
    }
}
