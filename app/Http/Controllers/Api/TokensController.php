<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\API\ApiMessages;
use App\Http\Controllers\Controller;
use App\Tokem;


class TokensController extends Controller
{
    public function __construct(Tokem $tokem = null)
    {
        $this->tokem = $tokem;
    }

    public function gerar_token($usuario_id)
    {
        try {
            $tokenData['usuario_id'] = $usuario_id;
            $tokenData['tokem'] = md5(bin2hex(random_bytes(17)));
            $result = Tokem::select('id')->where('usuario_id', '=', $usuario_id)->limit(1)->get();
            if ($result->isEmpty()) {
                Tokem::create($tokenData);
            } else {
                $tokenData['id'] = $result[0]->id;
                $token_encontrado = Tokem::select()->where('id', $tokenData['id'])->first();
                $token_encontrado->update($tokenData);
            }
            return $tokenData['tokem'];
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), $e->getCode()), 500);
            }
            return "Falha ao gerar token";
            if ($e->getCode() == 23000) {
                return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Usuario"), 422), 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public static function validar_token($token, $usuario_id)
    {
        try {
            $result = Tokem::select('tokem')
                ->where('tokem', '=', $token)
                ->limit(1)
                ->get();
            if (!$result->isEmpty()) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            // if (config('app.debug')) {
            //     return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            // }
            return false;
        }
    }
}
