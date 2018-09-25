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
          // hateoas
          'links' => [
            ['rel' => 'self', 'href' => route('buyers.show', $buyer->id)],
            ['rel' => 'buyer.categories', 'href' => route('buyers.categories.index', $buyer->id)],
            ['rel' => 'buyer.products', 'href' => route('buyers.products.index', $buyer->id)],
            ['rel' => 'buyer.sellers', 'href' => route('buyers.sellers.index', $buyer->id)],
            ['rel' => 'buyer.transactions', 'href' => route('buyers.transactions.index', $buyer->id)],
            ['rel' => 'user', 'href' => route('users.show', $buyer->id)],
          ]
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

    public static function transformedAttribute($index){

        $attributes = [
          'id' => 'identifier',
          'name' => 'fullname',
          'email' => 'emailaddress',
          'verified' => 'verified',
          'created_at' => 'createdDate',
          'updated_at' => 'updatedDate',
          'deleted_at' => 'deletedDate'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
