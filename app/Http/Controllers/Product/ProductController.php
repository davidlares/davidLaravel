<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductController extends ApiController
{
    public function index()
    {
        $product = Product::all();
        return $this->showAll($product, 200);
    }


    public function show(Product $product)
    {
        return $this->showOne($product, 200);
    }


}
