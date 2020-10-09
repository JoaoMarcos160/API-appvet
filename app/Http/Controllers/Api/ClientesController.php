<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\API\ApiMessages;
use App\Clientes;
use App\Http\Controllers\Controller;
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
        $data = ['data' => ApiMessages::message(1)];
        //na vdd vc precisa passar o id do usuario e ele trará os clientes
        return response()->json($data);
    }

    public function valida_token($token)
    {
        $result = TokensController::validar_token($token);
        if (!$result) {
            return false;
            // return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        }
        return true;
    }

    public function show(Clientes $id)
    {
        try {
            $data = ['data' => $id];
            return response()->json($data);
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
            $clienteData = $request->all();
            if (isset($clienteData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $this->cliente->create($clienteData);
                    return response()->json(['data' => ['msg' => ApiMessages::message(6)]], 201);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if ($e->getCode() == 'HY000') {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == '22007') {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function alterar(Request $request)
    {
        try {
            $clienteData = $request->all();
            if (isset($clienteData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $cliente_encontrado = $this->cliente->find($clienteData['id']);
                    if (isset($cliente_encontrado)) {
                        $cliente_encontrado->update($clienteData);
                        return response()->json(['data' => ['msg' => ApiMessages::message(9)]], 200);
                    }
                    return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Cliente"), 404), 404);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if ($e->getCode() == 'HY000') {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 22007) {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
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
            if (isset($clienteData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $cliente_encontrado = $this->cliente->find($clienteData['id']);
                    if (isset($cliente_encontrado)) {
                        $cliente_encontrado->delete($clienteData);
                        return response()->json(['data' => ['msg' => ApiMessages::message(11)]], 200);
                    }
                    return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Cliente"), 404), 404);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function listar_clientes(Request $request)
    {
        /*
        Esse listar_clientes deve receber o id do usuario, 
        e ele vai retornar todos os clientes
        vinculados a esse usuario
        No final das contas ele tem o mesmo resultado do buscar_clientes
        quando não passa nenhum parametro, apenas muda a performance
        */
        try {
            $clienteData = $request->all();
            if (isset($clienteData['token'])) {
                if ($this->valida_token(request('token'))) {
                    if (isset($clienteData['usuario_id'])) {
                        $clientes_encontrados = Clientes::where('usuario_id', request('usuario_id'))
                            ->orderBy('nome')
                            ->get();
                        // ->paginate(10); // se quiser usar paginação tem que tirar o ->get();
                        if (!$clientes_encontrados->isEmpty()) {
                            return response()->json(['data' => $clientes_encontrados], 200);
                        }
                        return response()->json(['data' => 'Nenhum cliente encontrado'], 200);
                    }
                    return \response()->json(["data" => ['msg' => ApiMessages::message(8)]], 422);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function buscar_cliente(Request $request)
    {
        try {
            $clienteData = $request->all();
            if (isset($clienteData['token'])) {
                if ($this->valida_token(request('token'))) {
                    if (isset($clienteData['usuario_id'])) {
                        //construindo a query
                        $query = Clientes::query();
                        $query->where('usuario_id', request('usuario_id'));
                        $query->when(
                            isset($clienteData['nome']),
                            function ($q) {
                                $nome = request('nome');
                                return $q->where('nome', 'like', "%$nome%");
                            }
                        );
                        $query->when(
                            isset($clienteData['cpf']),
                            function ($q) {
                                $cpf = request('cpf');
                                return $q->where('cpf', 'like', "%$cpf%");
                            }
                        );
                        $query->when(
                            isset($clienteData['telefone']),
                            function ($q) {
                                $telefone = request('telefone');
                                return $q->where('telefone', 'like', "%$telefone%");
                            }
                        );
                        $query->when(
                            isset($clienteData['endereco']),
                            function ($q) {
                                $endereco = request('endereco');
                                return $q->where('endereco', 'like', "%$endereco%");
                            }
                        );
                        $query->when(
                            isset($clienteData['numero']),
                            function ($q) {
                                $numero = request('numero');
                                return $q->where('numero', 'like', "%$numero%");
                            }
                        );
                        $query->when(
                            isset($clienteData['complemento']),
                            function ($q) {
                                $complemento = request('complemento');
                                return $q->where('complemento', 'like', "%$complemento%");
                            }
                        );
                        $query->when(
                            isset($clienteData['bairro']),
                            function ($q) {
                                $bairro = request('bairro');
                                return $q->where('bairro', 'like', "%$bairro%");
                            }
                        );
                        $query->when(
                            isset($clienteData['cidade']),
                            function ($q) {
                                $cidade = request('cidade');
                                return $q->where('cidade', 'like', "%$cidade%");
                            }
                        );
                        $query->when(
                            isset($clienteData['estado']),
                            function ($q) {
                                $estado = request('estado');
                                return $q->where('estado', 'like', "%$estado%");
                            }
                        );
                        $query->when(
                            isset($clienteData['dt_nasc']),
                            function ($q) {
                                $dt_nasc = request('dt_nasc');
                                return $q->whereDate('dt_nasc', $dt_nasc);
                            }
                        );
                        $query->when(
                            isset($clienteData['observacao']),
                            function ($q) {
                                $observacao = request('observacao');
                                return $q->where('observacao', 'like',  "%$observacao%");
                            }
                        );
                        $query->when(
                            isset($clienteData['email']),
                            function ($q) {
                                $email = request('email');
                                return $q->where('email', 'like',  "%$email%");
                            }
                        );
                        $query->when(
                            isset($clienteData['created_at']),
                            function ($q) {
                                $created_at = request('created_at');
                                return $q->whereDate('created_at',  $created_at);
                            }
                        );
                        $query->when(
                            isset($clienteData['updated_at']),
                            function ($q) {
                                $updated_at = request('updated_at');
                                return $q->whereDate('updated_at',  $updated_at);
                            }
                        );
                        $query->orderBy('nome');
                        $clientes_encontrados = $query->get();

                        /*
                AQUI EM BAIXO NESSE FOREACH ERA UMA TENTATIVA DE 
                LER O REQUEST E COLOCAR CADA PARAMETRO AUTOMATICAMENTE
                NA QUERY, MAS DECIDI FAZER CADA UMA SEPARADA PARA MAPEAR CERTO
                QUAIS OS PARAMETROS E OS CAMPOS DA CONSULTA, EVITANDO ASSIM BUGS.
                */
                        // foreach ($clienteData as $key => $value) {
                        //     $query->when(
                        //         isset($clienteData[$key]),
                        //         function ($q, $value, $key) {
                        //             return $q->where($key, 'like', "%$value%");
                        //         }
                        //     );
                        // }

                        if ($clientes_encontrados->isEmpty()) {
                            return response()->json(['data' => ["msg" => 'Nenhum cliente encontrado']], 404);
                        }
                        return response()->json(['data' => $clientes_encontrados]);
                    }
                    return \response()->json(["data" => ['msg' => ApiMessages::message(8)]], 422);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }
}
