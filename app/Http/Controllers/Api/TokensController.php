<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Token;


class TokensController extends Controller
{
    public function __construct(Token $token = null)
    {
        $this->token = $token;
    }

    public function gerar_token($usuario_id)
    {
        try {
            $tokenData['usuario_id'] = $usuario_id;
            $tokenData['token'] = md5(bin2hex(random_bytes(17)));
            $result = Token::select('id')->where('usuario_id', '=', $usuario_id)->limit(1)->get();
            if ($result->isEmpty()) {
                Token::create($tokenData);
            } else {
                $tokenData['id'] = $result[0]->id;
                $token_encontrado = Token::select()->where('id', $tokenData['id'])->first();
                $token_encontrado->update($tokenData);
            }
            return $tokenData['token'];
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), $e->getCode()), 500);
            }
            return "Falha ao gerar token";
        }
    }

    public static function validar_token($token, $usuario_id)
    {
        try {
            $result = Token::select('id')
                ->where('token', '=', $token)
                ->where('usuario_id', '=', $usuario_id)
                ->limit(1)
                ->get();
            if (!$result->isEmpty()) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return false;
        }
    }
}
