<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
      // fixing the corruption between keys at "transformations" and "database fields"
      $transformedInput = [];

      foreach ($request->request->all() as $input => $value) {
        // filling the array with data -> key -> value
        $transformedInput[$transformer::originalAttribute($input)] = $value;
      }

      $request->replace($transformedInput); // replacing the request object
    // checking the response
      $response = $next($request);

      if(isset($response->exception) && $response->exception instanceof ValidationException){

          $data = $response->getData();
          //transforming data
          $transformedErrors = [];
          // collecting data and assigning it to que transformed key
          foreach ($data->error as $field => $error) {
            $transformedField = $transformer::transformedAttribute($field);
            $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
          }

          // substitution
          $data->error = $transformedErrors;
          $response->setData($data);
      }

      return $response;
   }
}
