<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Clientes;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __construct(Clientes $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index()
    {
        // $data = ['data' => $this->cliente->paginate(10)];
        // $data = ['data' => $this->cliente->all()];
        // $data = ['data' => "Coloque na URL o id do cliente! Ex.: /api/clientes/25"];
        $data = ['data' => 'Você precisa estar logado!'];
        //na vdd vc precisa passar o id do usuario e ele trará os clientes
        return response()->json($data);
    }

    public function show(Clientes $id)
    {
        try {
            $data = ['data' => $id];
            // \print_r($data);
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
            $clienteData = $request->all();
            $this->cliente->create($clienteData);
            return response()->json(['data' => ['msg' => "Cliente criado com sucesso"]], 201);
        } catch (\Exception $e) {
            if ($e->getCode() == 'HY000') {
                return response()->json(["data" => ["msg" => "Faltou o id do usuário ou o nome do cliente", "code" => 1010]], 422);
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
            $clienteData = $request->all();
            $cliente_encontrado = $this->cliente->find($clienteData['id']);
            $cliente_encontrado->update($clienteData);
            return response()->json(['data' => ['msg' => "Cliente alterado com sucesso"]], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 200);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação  de ' . __FUNCTION__, 1010));
        }
    }

    public function deletar(Request $request)
    {
        /*
        Ao deletar um cliente, ele deletará todo o conteudo relacionado a esse cliente
        como por exemplo os animais referentes a esse cliente
        */
        try {
            // dd($request->all());
            $clienteData = $request->all();
            $cliente_encontrado = $this->cliente->find($clienteData['id']);
            if (isset($cliente_encontrado)) {
                $cliente_encontrado->delete($clienteData);
                return response()->json(['data' => ['msg' => "Cliente " . $request['id'] . " deletado com sucesso"]], 201);
            }
            return response()->json(ApiError::errorMessage("Cliente de id $clienteData[id] nao encontrado", 404), 404);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }

    public function listar_clientes(Request $request)
    {
        /*
        Esse listar_clientes deve receber o id do usuario, e ele vai retornar todos os usuarios
        vinculados a esse usuario
        */
        try {
            $clienteData = $request->all();
            $clientes_encontrados = Clientes::where('usuario_id', $clienteData['usuario_id'])
                ->get();
            // ->paginate(10); // se quiser usar paginação tem que tirar o ->get();
            // dd($clientes_encontrados);
            if (!$clientes_encontrados->isEmpty()) {
                return response()->json(['data' => $clientes_encontrados], 200);
            }
            return response()->json(['data' => 'Nenhum cliente encontrado'], 200);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010));
        }
    }
}
