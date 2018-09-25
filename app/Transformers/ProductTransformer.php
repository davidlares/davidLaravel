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
      ];
    }
}
