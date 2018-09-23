<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Traits\ApiResponser;

class Handler extends ExceptionHandler
{

    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // validation exceptions
        if($exception instanceof ValidationException){
          return $this->convertValidationExceptionToResponse($exception, $request);
        }

        // model exceptions
        if($exception instanceof ModelNotFoundException){
          $model = strtolower(class_basename($exception->getModel()));
          return $this->errorResponse("No instance from {$model}, with specified ID", 404);
        }

        if($exception instanceof AuthenticationException){
          return $this->unauthenticated($request,$e);
        }

        // authorization exceptions
        if($exception instanceof AuthorizationException) {
          return $this->errorResponse("User does not have permissions to execute this action", 403);
        }

        // not found (model instance of non existence routes)
        if($exception instanceof NotFoundHttpException) {
          return $this->errorResponse("URL not found", 404);
        }

        // not allowed methods
        if($exception instanceof MethodNotAllowedHttpException){
          return $this->errorResponse("URL method not valid", 405);
        }

        // general exceptions
        if($exception instanceof HttpException){
          return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        // query exception
        if($exception instanceof QueryException){
          // fk and unconsistent data
          $code = $exception->errorInfo[1];
          if($code == 1451){
            return $this->errorResponse('Unable to delete, related data on resource', 409);
          }
        }

        // unexpected error
        if(config('app.debug')){
          return parent::render($request, $exception);
        } else {
          return $this->errorResponse('Unexpected error, try again later', 500);
        }
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request){
      $errors = $e->validator->errors()->getMessages();
      return $this->errorResponse($errors, 422);
    }
}
