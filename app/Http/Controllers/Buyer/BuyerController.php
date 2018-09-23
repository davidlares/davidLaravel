 <?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use App\Buyer;

class BuyerController extends ApiController
{
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();
        // return response()->json(['data' => $buyers], 200);
        return $this->showAll($buyers);
    }

    public function show($id)
    {
        $buyer = Buyer::has('transactions')->findOrFail($id);
        // return response()->json(['data' => $buyer], 200);
        return $this->showOne($buyer, 200);
    }

}
