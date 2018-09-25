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
      // is good to abstract the custom API answer with no mix of resources

      if($collection->isEmpty()){
        return $this->successResponse(['data' => $collection], $code);
      } else {
        // model transformer
        $transformer = $collection->first()->transformer;
        // filtering data with multiples values
        $collection = $this->filterData($collection, $transformer);
        // it should be executed b4 the fractal = laravel.api/api/users?sort_by=name
        $collection = $this->sortData($collection, $transformer);
        // setting the transformData method
        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
      }
    }

    // show only instance
    protected function showOne(Model $instance, $code = 200){
      $transformer = $instance->transformer;
      $instance = $this->transformData($instance, $transformer);
      return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200){
      return $this->successResponse(['data' => $message], $code);
    }

    // transformed responses
    protected function transformData($data, $transformer){
      $transformation = fractal($data, new $transformer); // build transformation instance from fractal
      return $transformation->toArray();
    }

    protected function sortData(Collection $collection, $transformer){
      if(request()->has('sort_by')){
        $attr = $transformer::originalAttribute(request()->sort_by);
        $collection = $collection->sortBy->{$attr};
      }

      return $collection;
    }

    protected function filterData(Collection $collection, $transformer){
      foreach (request()->query() as $query => $value) {
        $attr = $transformer::originalAttribute($query);
        if(isset($attr, $value)){
          $collection = $collection->where($attr, $value);
        }
      }

      return $collection;
    }
}
