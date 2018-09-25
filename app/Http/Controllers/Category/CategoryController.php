<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{
    /* using php artisan make:controller Category/CategoryController --resource --model=Category */
    /* Model Dependency Injection */

    public function __construct(){
      parent::__construct(); // parent (Controller) construct method
      $this->middleware('transform.input:' . CategoryTransformer::class)->only(['store','update']);
    }

    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required',
          'description' => 'required'
        ]);

        $category = Category::create($request->all());
        return $this->showOne($category, 201);

    }

    public function show(Category $category)
    {
        return $this->showOne($category, 200);
    }

    public function update(Request $request, Category $category)
    {
        // fill the instance
        // intersect -> values (name, description) = category could be updated with submitted values
        $category->fill($request->intersect([
          'name',
          'description'
        ]));

        // isDirty (instance changed), isClean(instance not Changed)
        if($category->isClean()){
          return $this->errorResponse('You must set at least a different value to update', 422);
        }

        $category->save();
        return $this->showOne($category);


    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category, 200);

    }
}
