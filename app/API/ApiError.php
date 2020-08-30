<?php

namespace App\API;

class ApiError
{
    public static function errorMessage($mensagem, $codigo)
    {
        return ['data' => [
            'msg' => $mensagem,
            'code' => $codigo,
        ]];
    }
}
