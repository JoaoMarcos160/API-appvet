<?php

namespace App\Exceptions;

use App\API\ApiError;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // dd($exception);
        if ($request->is('api/usuarios/*')) {
            if ($exception->getCode() == 0) {
                //isso aqui significa que não encontrou nenhum usuário com esse id
                return \response()->json(ApiError::errorMessage('Usuário não encontrado', 404));
            } else {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()));
                }
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 404);
            }
        }
        if ($request->is('api/clientes/*')) {
            if ($exception->getCode() == 0) {
                //isso aqui significa que não encontrou nenhum usuário com esse id
                return \response()->json(ApiError::errorMessage('Cliente não encontrado', 404));
            } else {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()));
                }
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 404);
            }
            // ou utilizar o erro padrão passando seu erro personalizado
            // return parent::render($request, $myexception);
        }
        //Erro padrão sem retorno personalizado
        //Nesse caso aqui o Laravel retorna a pagina 404
        return parent::render($request, $exception);
    }
}
