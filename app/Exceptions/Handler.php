<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // instead of this the HttpException i run. its not the best way bu what you could do is check if the status code is 419 in HttpException do the  TokenMismatchException stuff
        $this->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->back()->withInput($request->input());
        });
        
        $this->renderable(function (ValidationException $e, $request) {
            if($this->isFrontend($request)){
                return $request->ajax() ? response()->json($e->errors(), $e->status) : redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors($e->errors());
            }
            return $this->errorResponse($e->errors(), $e->status);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return $this->errorResponse('The specified method for the request is invalid', 405);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return $this->errorResponse('The resources you are trying to find does not exist', 404);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if($this->isFrontend($request)){
                return redirect()->guest('login');
            }

            return $this->errorResponse('Unauthenticated', 401);
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            return $this->errorResponse($e->getMessage(), 403);
        });

        $this->renderable(function (HttpException $e, $request) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        });

        $this->renderable(function (QueryException $e, $request) {
            $errorCode = isset($e->errorInfo[1]) ? $e->errorInfo[1] : false;

            if($errorCode == 1451){
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource', 409);
            }
        });

        if(!config('app.debug')){
            $this->renderable(function (Throwable $e) {
                return $this->handleUnexpectedException($e);
            });
        }
    }

    protected function handleUnexpectedException(Throwable $e){
        return $this->errorResponse('Unexpected Exception, Try Later', 500);
    }

    protected function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
