<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerProductController extends ApiController
{
    protected function verifyVendor(Seller $seller, Product $product){
      if($seller->id != $product->seller_id){
        throw new HttpException(422,'Err Processing Request');
        // return $this->errorResponse('Is not the real seller of the product',422);
      }
    }

    public function index(Seller $seller){
       $products = $seller->products;
       return $this->showAll($products, 200);
    }

    // users without sells
    public function store(Request $request, User $seller){

        $this->validate($request,[
          'name' => 'required',
          'description' => 'required',
          'quantity' => 'required|integer|min:1',
          'image' => 'required|image'
        ]);

        $data = $request->all();
        $data['status'] = Product::NOT_AVAILABLE;
        $data['image'] = '1.jpg';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product, 201);

    }

    public function update(Request $request, Seller $seller, Product $product){

      $this->validate($request,[
        'quantity' => 'required|integer|min:1',
        'status' => 'in: '. Product::AVAILABLE, ' , '. Product::NOT_AVAILABLE,
        'image' => 'image'
      ]);

      $this->verifyVendor($seller,$product);
      $product->fill($request->intersect(['name','description','quantity']));

      if($request->has('status')){
        $product->status = $request->status;
        if($product->isAvailable() && $product->categories()->count() == 0){
          return $this->errorResponse('An active product must have at least one category', 409);
        }
      }
      if($product->isClean()){
        return $this->errorResponse('You must specify a different value to update', 422);
      }

      $product->save();
      return $this->showOne($product);

    }

    public function destroy(Seller $seller, Product $product){
      $this->verifyVendor($seller,$product);
      $product->delete();
      return $this->showOne($product);
    }
}
