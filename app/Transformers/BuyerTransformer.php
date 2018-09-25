<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
      return [
          'identifier' => (int)$buyer->id,
          'fullname' => (string)$buyer->name,
          'emailaddress' => (string)$buyer->email,
          'verified' => (int)$buyer->verified,
          'createdDate' => (string)$buyer->created_at,
          'updatedDate' => (string)$buyer->updated_at,
          'deletedDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
      ];
    }

    public static function originalAttribute($index){
        $attributes = [
          'identifier' => 'id',
          'fullname' => 'name',
          'emailaddress' => 'email',
          'verified' => 'verified',
          'createdDate' => 'created_at',
          'updatedDate' => 'updated_at',
          'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
