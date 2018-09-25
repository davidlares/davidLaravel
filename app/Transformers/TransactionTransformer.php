<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
      return [
          'identifier' => (int)$transaction->id,
          'quantity' => (string)$transaction->quantity,
          'buyer' => (string)$transaction->buyer_id,
          'producto' => (string)$transaction->product_id,
          'verified' => (int)$transaction->verified,
          'createdDate' => (string)$transaction->created_at,
          'updatedDate' => (string)$transaction->updated_at,
          'deletedDate' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
          // hateoas
          'links' => [
            ['rel' => 'self', 'href' => route('transactions.show', $transaction->id)],
            ['rel' => 'transaction.categories', 'href' => route('transactions.categories.index', $transaction->id)],
            ['rel' => 'transaction.seller', 'href' => route('transactions.sellers.index', $transaction->id)],
            ['rel' => 'buyer', 'href' => route('buyer.show', $transaction->seller_id)]
            ['rel' => 'product', 'href' => route('products.show', $transaction->product_id)]

          ]
      ];
    }

    public static function originalAttribute($index){
        $attributes = [
          'identifier' => 'id',
          'quantity' => 'quantity',
          'buyer' => 'buyer_id',
          'producto' => 'product_id',
          'verified' => 'verified',
          'createdDate' => 'created_at',
          'updatedDate' => 'updated_at',
          'deletedDate' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
           'id' => 'identifier',
           'quantity' => 'quantity',
           'buyer_id' => 'buyer',
           'product_id' => 'producto',
           'verified' => 'verified',
           'created_at' => 'createdDate',
           'updated_at' => 'updatedDate',
           'deleted_at' => 'deletedDate'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
