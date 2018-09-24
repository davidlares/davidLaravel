<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Traits\ApiResponser;

class CustomThrottleMiddleware extends ThrottleRequests
{
    use ApiResponser;

    protected function buildResponse($key, $maxAttempts) {

      $response = $this->errorResponse("Too manu Attempts", 429);
      $retryAfter = $this->limiter->availableIn($key);
      return $this->addHeaders($response, $maxAttempts,
      $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter), $retryAfter);

    }
}
