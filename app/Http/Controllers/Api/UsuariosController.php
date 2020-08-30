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

    public function index()
    {
        // $data = ['data' => $this->usuario->paginate(10)];
        $data = ['data' => $this->usuario->all()];
        return response()->json($data);
    }

    public function show(Usuarios $id)
    {
        $data = ['data' => $id];
        return response()->json($data);
    }

    public function store(Request $request)
    {

        try {
            // dd($request->all());
            $usuarioData = $request->all();
            $this->usuario->create($usuarioData);
            return response()->json(['data' => ['msg' => "Usuario criado com sucesso"]], 201);
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação', 1010));
        }
    }
}
