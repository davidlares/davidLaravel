<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    public function index(Buyer $buyer)
    {
        // eager loading -> include related relations existence
        // querybuilder instead of collections => transactions()
        $products = $buyer->transactions()->with('product')->get()->pluck('product');
        return $this->showAll($products, 200);
    }

}
