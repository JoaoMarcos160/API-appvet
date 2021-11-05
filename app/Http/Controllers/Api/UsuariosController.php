<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\API\ApiMessages;
use App\Http\Controllers\Controller;
use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{

    public function __construct(Usuarios $usuario)
    {
        $this->usuario = $usuario;
    }

    public function valida_token($token, $usuario_id)
    {
        $result = TokensController::validar_token($token, $usuario_id);
        if (!$result) {
            return false;
            // return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        }
        return true;
    }

    public function index() //listava todos os usuarios
    {
        // $data = ['data' => $this->usuario->paginate(10)];
        // $data = ['data' => $this->usuario->all()];
        $data = ['data' => ApiMessages::message(1)];
        return response()->json($data, 200);
    }

    public function show(Usuarios $id)
    {
        try {
            $data = ['data' => $id];
            return \response()->json($data, 200);
            //Tratamento de usuario não encontrado colocado dentro de app\Exceptions\Handler.php
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function login()
    {
        // return response()->json(['data' => ['msg' => "sucesso"]], 201);
        try {
            // $usuarioData = $request->all();
            $usuario_encontrado = Usuarios::select('id', 'login', 'senha')
                ->where('login', request('login'))
                ->get();
            // dd($usuario_encontrado);
            if ($usuario_encontrado->isEmpty()) {
                return response()->json(['data' => ['msg' => ApiMessages::message(3)]], 200);
            }
            if (Hash::check(request('senha'), $usuario_encontrado[0]->senha)) {
                // print_r($usuario_encontrado[0]->id);
                $controller = new TokensController();
                $token_gerado = $controller->gerar_token($usuario_encontrado[0]->id);
                return response()->json([
                    'data' =>
                    [
                        'id' => $usuario_encontrado[0]->id,
                        'msg' => ApiMessages::message(4), //mensagem de sucesso
                        'token' => $token_gerado,
                    ]
                ], 200);
            }
            return response()->json(['data' => ['msg' => ApiMessages::message(5)]], 200);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function criar(Request $request)
    {
        try {
            $usuarioData = $request->all();
            $logins_encontrados = Usuarios::select('id')
                ->where('login', request('login'))
                ->get();
            if ($logins_encontrados->isEmpty()) {
                $usuarioData['senha'] = Hash::make($usuarioData['senha']);
                $usuario_criado = $this->usuario->create($usuarioData);
                $controller = new TokensController();
                $token_gerado = $controller->gerar_token($usuario_criado->id);
                return response()->json(['data' => [
                    'id' => $usuario_criado->id,
                    'token' => $token_gerado,
                    'msg' => ApiMessages::message(6)
                ]], 201);
            } else {
                return response()->json(['data' => ['msg' => ApiMessages::message(7), 'code' => 1020]], 200);
            }
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 23000) {
                return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Usuario"), 422), 422);
            }
            if ($e->getCode() == 'HY000') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 422), 422);
            }
            if ($e->getCode() == 22007) {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function alterar(Request $request)
    {
        try {
            $usuarioData = $request->all();
            if (isset($usuarioData['token'])) {
                if ($this->valida_token(request('token'), request('usuario_id'))) {
                    $usuario_encontrado = $this->usuario->find($usuarioData['id']);
                    if ($usuario_encontrado == null) {
                        return response()->json(['data' => ['msg' => ApiMessages::message(12, "Usuario")]], 201);
                    }
                    if (isset($usuarioData['senha'])) {
                        $usuarioData['senha'] = Hash::make($usuarioData['senha']);
                    }
                    $usuario_encontrado->update($usuarioData);
                    return response()->json(['data' => ['msg' => ApiMessages::message(9)]], 201);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 23000) {
                return response()->json(ApiError::errorMessage(ApiMessages::message(10), 422), 422);
            }
            if ($e->getCode() == '22007') {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function deletar(Request $request)
    {
        /*
        Ao deletar um usuario, ele deletará todo o conteudo relacionado a esse usuario
        como por exemplo os clientes, e os animais referentes a esses clientes
        */
        try {
            // dd($request->all());
            $usuarioData = $request->all();
            if (isset($usuarioData['token'])) {
                if ($this->valida_token(request('token'), request('usuario_id'))) {
                    $usuario_encontrado = $this->usuario->find($usuarioData['id']);
                    if (isset($usuario_encontrado)) {
                        $usuario_encontrado->delete($usuarioData);
                        return response()->json(['data' => ['msg' => ApiMessages::message(11)]], 200);
                    }
                    return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Usuario"), 404), 404);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function ok()
    {
        //função só pra conferir se a api está online
        return response()->json(["status" => true]);
    }
}
