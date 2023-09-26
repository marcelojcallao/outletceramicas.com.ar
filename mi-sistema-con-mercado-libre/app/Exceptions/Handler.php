<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ClientException;
use App\Exceptions\MercadoLibre\UpdatePriceAndQuantityException;
use App\Exceptions\MercadoLibre\GetPublicationsFromMeliException;
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
        /* if ($exception instanceof UpdatePriceAndQuantityException) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }

        if ($exception instanceof GetPublicationsFromMeliException) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }

        if ($exception instanceof ClientException) {
            return $this->handleClientException($exception, $request);
        } */
        /* if($request->expectsJson())
        {
            if($exception instanceof AuthenticationException)
            {
                return redirect('/login');
            }
           
        } */

        return parent::render($request, $exception);
    }

    /* public function handleClientException(ClientException $exception, $request)
    {
        $code = $exception->getCode();

        $response = json_decode($exception->getBody()->getContents());

        $errorMessage = $response->error;

        switch ($code) {
            case Response::HTTP_UNAUTHORIZED:
                $response->session()->invalidate();

                if ($request->user) {
                    Auth::logout();
                    return redirect()
                        ->route('welcome')
                        ->withErrors(['message' => 'Falló autenticación, por favor intente loguearse otra vez.']);
                }
                break;
            
            default:
                # code...
                break;
        }
    } */
}
