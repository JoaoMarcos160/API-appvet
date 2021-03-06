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
                return \response()->json(ApiError::errorMessage('Usuário não encontrado', 404), 404);
            } else if ($exception->getCode() == 42000) {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage('Erro no SQL', 500), 500);
            } else {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 404);
            }
        }
        if ($request->is('api/clientes/*')) {
            if ($exception->getCode() == 0) {
                //isso aqui significa que não encontrou nenhum usuário com esse id
                return \response()->json(ApiError::errorMessage('Cliente não encontrado', 404), 404);
            } else if ($exception->getCode() == 42000) {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage('Erro no SQL', 500), 500);
            } else {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 404);
            }
        }
        if ($request->is('api/animais/*')) {
            if ($exception->getCode() == 0) {
                //isso aqui significa que não encontrou nenhum usuário com esse id
                return \response()->json(ApiError::errorMessage('Animal não encontrado', 404), 404);
            } else if ($exception->getCode() == 42000) {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage('Erro no SQL', 500), 500);
            } else {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 500);
            }
        }
        if ($request->is('api/consultas/*')) {
            if ($exception->getCode() == 0) {
                //isso aqui significa que não encontrou nenhum usuário com esse id
                return \response()->json(ApiError::errorMessage('Consulta não encontrada', 404), 404);
            } else if ($exception->getCode() == 42000) {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage('Erro no SQL', 500), 500);
            } else {
                if (config('app.debug')) {
                    return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
                }
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 500);
            }
        }
        // Descomentar essa linha em produção
        // return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 400);

        // Erro padrão sem retorno personalizado
        // Nesse caso aqui o Laravel retorna a pagina 404
        return parent::render($request, $exception);
    }
}
