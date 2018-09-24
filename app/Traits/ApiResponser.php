<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser {

    // api responses

    private function successResponse($data, $code){
      return response()->json($data, $code);
    }

    private function errorResponse($message, $code){
      return response()->json(['error' => $message, 'code', $code], $code);
    }

    // show multiple records
    protected function showAll(Collection $collection, $code = 200){
      return $this->successResponse(['data' => $collection], $code);
    }

    // show only instance
    protected function showOne(Model $instance, $code = 200){
      return $this->successResponse(['data' => $instance], $code);
    }

    protected function showMessage($message, $code = 200){
      return $this->successResponse(['data' => $message], $code);
    }
}
