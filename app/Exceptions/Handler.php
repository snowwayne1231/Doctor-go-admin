<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\AuthException;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // dd($exception);
        // Log::error($exception);
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
        if (isset($request->uuid)) {
            header('Access-Control-Allow-Origin: *');
            // header('Access-Control-Allow-Methods: *');
            // header('Access-Control-Allow-Headers: *');
            $http_code = 501;
            switch (true) {
                case $exception instanceof \Illuminate\Validation\ValidationException:
                    return parent::render($request, $exception);
                case $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException:
                    $http_code = 404;
                    $render_result = [];
                    break;
                case $exception instanceof AuthException:
                    $render_result = [
                        'errors' => $exception->getMessage(),
                        'class' => get_class($exception),
                        'redirect' => '/',
                        'logout' => true,
                    ];
                    break;
                default:
                    $render_result = [
                        'trace' => $exception->getTrace(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'errors' => $exception->getMessage(),
                        'class' => get_class($exception),
                    ];
            }
            
            return response($render_result, $http_code);
        }
        
        return parent::render($request, $exception);
    }
}
