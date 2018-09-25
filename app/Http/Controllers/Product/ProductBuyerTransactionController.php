<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Transactions\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct(){
      parent::__construct(); // parent (Controller) construct method
      $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }

    public function store(Request $request, Product $product, User $buyer) {

       $rules = [
         'quantity' => 'required|integer|min:1'
       ];

       $this->validate($request,$rules);

       if($buyer->id == $product->seller_id){
         return $this->errorResponse('Buyer and Seller must be different', 409);
       }

       if(!$buyer->isVerified()){
         return $this->errorResponse('Buyer must ver a verified user', 409);
       }

       if(!$product->seller->isVerified()){
         return $this->errorResponse('Seller must be a verified user', 409);
       }

       if(!$product->isAvailable()) {
         return $this->errorResponse('Product not available for this action', 409);
       }

       if($product->quantity < $request->quantity){
         return $this->errorResponse('Not a quantity required for this transaction', 409);
       }

       return DB::transaction(function() use ($request, $product, $buyer) {

         $product->quantity = $request->quantity;
         $product->save();
         $transaction = Transaction::create([
            'quantity' => $request->quantity,
            'buyer_id' => $buyer_id,
            'product_id' => $product->id
         ]);

         return $this->showOne($transaction, 201);
       });
     }
 }
