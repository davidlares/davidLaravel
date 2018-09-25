<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
      return [
          'identifier' => (int)$seller->id,
          'fullname' => (string)$seller->name,
          'emailaddress' => (string)$seller->email,
          'verified' => (int)$seller->verified,
          'createdDate' => (string)$seller->created_at,
          'updatedDate' => (string)$seller->updated_at,
          'deletedDate' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
          // hateoas
          'links' => [
            ['rel' => 'self', 'href' => route('buyers.show', $seller->id)],
            ['rel' => 'seller.categories', 'href' => route('sellers.categories.index', $seller->id)],
            ['rel' => 'seller.products', 'href' => route('sellers.products.index', $seller->id)],
            ['rel' => 'seller.products', 'href' => route('sellers.products.index', $seller->id)],
            ['rel' => 'seller.transactions', 'href' => route('sellers.transactions.index', $seller->id)],
            ['rel' => 'user', 'href' => route('users.show', $seller->id)],
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
