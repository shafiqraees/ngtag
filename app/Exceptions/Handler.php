<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        $this->report($exception);
        if( $request->has('password') ){
            $masked_password = Str::mask($request->get('password'), '*', 0);
            $request->offsetSet('password', $masked_password);
        }
        if($exception instanceof ModelNotFoundException) {
            $model = app($exception->getModel());
            return response()->json([
                'message' => method_exists($model, 'notFoundMessage') ? $model->notFoundMessage() : $this->ModelNotFoundExceptionResponse(),
                'success' => false,
                'status_code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        if($exception instanceof RouteNotFoundException){
            return response()->json([
                'message' => __('response_messages.route_not_found'),
                'success' => false,
                'status_code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        if($exception instanceof \BadMethodCallException){
            return response()->json([
                'message' => __('response_messages.method_not_found'),
                'success' => false,
                'status_code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return response()->json([
                'message' => __('response_messages.method_not_allowed'),
                'success' => false,
                'status_code' => Response::HTTP_METHOD_NOT_ALLOWED
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }
        if($exception instanceof NotFoundHttpException){
            return response()->json([
                'message' => __('response_messages.not_found'),
                'success' => false,
                'status_code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        if($exception instanceof AuthenticationException){
            return response()->json([
                'message' => __('response_messages.unauthorized'),
                'success' => false,
                'status_code' => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_UNAUTHORIZED);
        }
        if($exception instanceof ThrottlesExceptions || $exception instanceof ThrottleRequestsException){
            return response()->json([
                'message' => __('response_messages.too_many_request'),
                'success' => false,
                'status_code' => Response::HTTP_TOO_MANY_REQUESTS
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        return parent::render($request, $exception);
    }

    protected function ModelNotFoundExceptionResponse(){
        return "Resource not found. Please provide valid resource ID.";
    }
}
