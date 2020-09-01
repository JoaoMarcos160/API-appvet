<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Usuarios;
use Illuminate\Http\Request;

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
            return response()->json($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }

    public function criar(Request $request)
    {
        try {
            // dd($request->all());
            $usuarioData = $request->all();
            $this->usuario->create($usuarioData);
            return response()->json(['data' => ['msg' => "Usuario criado com sucesso"]], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }

    public function alterar(Request $request)
    {
        try {
            // dd($request->all());
            $usuarioData = $request->all();
            $usuario_encontrado = $this->usuario->find($usuarioData['id']);
            // print_r($usuario_encontrado);
            $usuario_encontrado->update($usuarioData);
            return response()->json(['data' => ['msg' => "Usuario alterado com sucesso"]], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação  de ' . __FUNCTION__, 1010));
        }
    }

    public function deletar(Request $request)
    {
        try {
            // dd($request->all());
            $usuarioData = $request->all();
            $usuario_encontrado = $this->usuario->find($usuarioData['id']);
            // print_r($usuario_encontrado);
            $usuario_encontrado->delete($usuarioData);
            return response()->json(['data' => ['msg' => "Usuario " . $request['id'] . " deletado com sucesso"]], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }
}
