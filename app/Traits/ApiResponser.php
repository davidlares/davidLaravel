<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
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
        // paginating data
        $collection = $this->paginate($collection);
        // setting the transformData method
        $collection = $this->transformData($collection, $transformer);

        // cache reponse
        $collection = $this->cacheResponse($collection);
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

    protected function paginate(Collection $collection){

      $rules = [
        'per_page' => 'integer|min:2|max:50'
      ];

      Validator::validate(request()->all(), $rules);

      // length aware paginator
      $page = LengthAwarePaginator::resolveCurrentPage();
      $perPage = 15;

      if(request()->has('per_page')){
        $perPage = (int)request()->per_page;
      }

      $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
      $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath()
      ]);
      $paginated->appends(request()->all());
      return $paginated;
    }

    protected function cacheResponse($data){
      $url = request()->url(); // actual url
      $params = request()->query();

      ksort($params); // ordering by key (array) -> by reference
      $params = http_build_query($params); // creates the queryparams URI
      $fullUrl = "{$url}?{$params}";

      return Cache::remember($fullUrl ,15/60, function() use ($data){
        // data to cache -> in 15 segs by the URL (index or key)
        return $data;
      });
    }
}
