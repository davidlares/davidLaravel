<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    public function index(Product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories, 200);
    }

    public function update(Request $request, Product $product, Category $category){

      // sync, attach, syncWithoutDetaching
      $product->categories()->syncWithoutDetaching([$category->id]);
      // return all categories
      return $this->showAll($product->categories);
    }

    public function destroy(Request $request, Product $product, Category $category){
      if(!$product->categories()->find($category->id)){
        return $this->errorResponse('Cat specified is not from this product', 404);
      }
      $product->categories()->detach([$category->id]);
      return $this->showAll($product->categories); 
    }

}
