<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottleMiddleware extends ThrottleRequests
{
    use ApiResponser;

    protected function buildResponse($key, $maxAttempts) {

      $response = $this->errorResponse("Too many Attempts", 429);
      $retryAfter = $this->limiter->availableIn($key);
      return $this->addHeaders(
        $response, $maxAttempts,
        $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
        $retryAfter
      );

    }
}
