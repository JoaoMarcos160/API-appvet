<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Usuarios;
use Facade\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{

    public function __construct(Usuarios $usuario)
    {
        $this->usuario = $usuario;
    }

    public function index() //listava todos os usuarios
    {
        // $data = ['data' => $this->usuario->paginate(10)];
        // $data = ['data' => $this->usuario->all()];
        $data = ['data' => "Coloque na URL o id do usuario! Ex.: /api/usuarios/25"];
        return response()->json($data);
    }

    public function show(Usuarios $id)
    {
        try {
            $data = ['data' => $id];
            return \response()->json($data);
            //Tratamento de usuario não encontrado colocado dentro de app\Exceptions\Handler.php
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }

    public function login(Request $request)
    {
        // return response()->json(['data' => ['msg' => "sucesso"]], 201);
        try {
            $usuarioData = $request->all();
            $usuario_encontrado = Usuarios::where('login', $usuarioData['login'])
                ->get();
            // dd($usuario_encontrado);
            if ($usuario_encontrado->isEmpty()) {
                return response()->json(['data' => ['msg' => 'Login não encontrado']], 201);
            }
            if (Hash::check($usuarioData['senha'], $usuario_encontrado[0]->senha)) {
                //colocar geração de token de autenticação aqui
                return response()->json(['data' => ['msg' => 'Sucesso']], 201);
            }
            return response()->json(['data' => ['msg' => 'Senha incorreta']], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação  de ' . __FUNCTION__, 1010));
        }
    }

    public function criar(Request $request)
    {
        try {
            $usuarioData = $request->all();
            $usuarioData['senha'] = Hash::make($usuarioData['senha']);
            $this->usuario->create($usuarioData);
            return response()->json(['data' => ['msg' => "Usuario criado com sucesso"]], 201);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json(ApiError::errorMessage("Já existe um usuario com esse login", 422), 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }

    public function alterar(Request $request)
    {
        try {
            $usuarioData = $request->all();
            $usuario_encontrado = $this->usuario->find($usuarioData['id']);
            if (isset($usuarioData['senha'])) {
                $usuarioData['senha'] = Hash::make($usuarioData['senha']);
            }
            $usuario_encontrado->update($usuarioData);
            return response()->json(['data' => ['msg' => "Usuario alterado com sucesso"]], 201);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json(ApiError::errorMessage("Já existe um usuario com esse login", 422), 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 200);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação  de ' . __FUNCTION__, 1010));
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
            $usuario_encontrado = $this->usuario->find($usuarioData['id']);
            if (isset($usuario_encontrado)) {
                $usuario_encontrado->delete($usuarioData);
                return response()->json(['data' => ['msg' => "Usuario " . $request['id'] . " deletado com sucesso"]], 201);
            }
            return response()->json(ApiError::errorMessage("Usuario de id $usuarioData[id] nao encontrado", 404));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }
}
