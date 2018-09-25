<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
      //laravel helper to transform the image to a URL
      return [
          'identifier' => (int)$product->id,
          'product' => (string)$product->name,
          'details' => (string)$product->description,
          'available' => (string)$product->quantity,
          'status' => (string)$product->status,
          'image' => url("img/{$product->image}"),
          'seller' => (int)$product->seller_id,
          'createdDate' => (string)$product->created_at,
          'updatedDate' => (string)$product->updated_at,
          'deletedDate' => isset($product->deleted_at) ? (string) $product->deleted_at : null,
          // hateoas
          'links' => [
            ['rel' => 'self', 'href' => route('products.show', $product->id)],
            ['rel' => 'products.buyers', 'href' => route('products.buyers.index', $product->id)],
            ['rel' => 'products.categories', 'href' => route('products.categories.index', $product->id)],
            ['rel' => 'products.transactions', 'href' => route('products.transactions.index', $product->id)],
            ['rel' => 'seller', 'href' => route('sellers.show', $product->seller_id)]
          ]
      ];
    }

    public static function originalAttribute($index){
        $attributes = [
          'identifier' => 'id',
          'product' => 'name',
          'details' => 'description',
          'available' => 'quantity',
          'status' => 'status',
          'image' => 'image',
          'seller' => 'seller_id',
          'createdDate' => 'created_at',
          'updatedDate' => 'updated_at',
          'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identifier',
            'name' => 'product',
            'description' => 'details',
            'quantity' => 'available',
            'status' => 'status',
            'image' => 'image' ,
            'seller_id' => 'seller',
            'created_at' => 'createdDate',
            'updated_at' => 'updatedDate',
            'deleted_at' => 'deletedDate'

        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
