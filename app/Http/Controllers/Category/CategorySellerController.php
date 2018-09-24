<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{
    public function index(Category $category)
    {
        $sellers = $category->products()->with('seller')->get()->pluck('seller')->unique()->values();
        // values() -> rebuild records indexes that could be lost within the eager relation
        return $this->showAll($sellers,200);
    }

}
