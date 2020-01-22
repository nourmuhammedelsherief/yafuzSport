<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        return parent::render($request, $exception);
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()){
//            return response()->json(['error' => 'Unauthenticated.'], 401);
            $errors = [
                'key'=>'token',
                'value'=>trans('messages.token_is_required'),
            ];

            http_response_code(401);  // set the code
            return response()->json(['code' =>  http_response_code()  , 'data' => [], 'error' => array($errors)])->setStatusCode(401);

        }
        $guard = array_get($exception->guards(),0);
        switch ($guard){
            case 'admin':
                $login = 'admin.login';
                break;
            case 'sawaq':
                $errors = [
                    'key'=>'token',
                    'value'=>trans('messages.token_is_required'),
                ];

                http_response_code(401);  // set the code
                return response()->json(['code' =>  http_response_code()  , 'data' => [], 'error' => array($errors)])->setStatusCode(401);

            default:
                $errors = [
                    'key'=>'token',
                    'value'=>trans('messages.token_is_required'),
                ];

                http_response_code(401);  // set the code
                return response()->json(['code' =>  http_response_code()  , 'data' => [], 'error' => array($errors)])->setStatusCode(401);

        }
        return redirect()->guest(route($login))->with('error', trans('messages.You_should_login_first'));
    }
}
