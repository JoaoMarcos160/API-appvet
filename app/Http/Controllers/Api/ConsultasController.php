<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Consultas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
    public function __construct(Consultas $consulta)
    {
        $this->consulta = $consulta;
    }

    public function index()
    {
        // $data = ['data' => $this->consulta->paginate(10)];
        // $data = ['data' => $this->consulta->all()];
        // $data = ['data' => "Coloque na URL o id da consulta! Ex.: /api/consultas/25"];
        $data = ['data' => 'Você precisa estar logado!'];
        return response()->json($data);
    }

    public function show(Consultas $id)
    {
        try {
            $data = ['data' => $id];
            return response()->json($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function listar_consultas(Request $request)
    {
        /*
        Esse listar_consultas deve receber o id do animal, 
        e ele vai retornar todos os animais
        vinculados a esse animal
        No final das contas ele tem o mesmo resultado do buscar_consultas
        quando não passa nenhum parametro, apenas muda a performance
        */
        try {
            $consultaData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    if (isset($consultaData['animal_id'])) {
                        $consultas_encontradas = Consultas::where('animal_id', request('animal_id'))
                            ->orderByDesc('created_at')
                            ->get();
                        if (!$consultas_encontradas->isEmpty()) {
                            return response()->json(['data' => $consultas_encontradas], 200);
                        }
                        return response()->json(['data' => 'Nenhuma consulta encontrada'], 200);
                    }
                    return response()->json(['data' => ['msg' => 'Falta um id do animal']], 422);
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

    public function buscar_consulta(Request $request)
    {
        try {
            $consultaData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    if (isset($consultaData['animal_id'])) {
                        //construindo a query
                        $query = Consultas::query();
                        $query->where('animal_id', request('animal_id'));
                        $query->when(
                            isset($consultaData['observacao']),
                            function ($q) {
                                $observacao = request('observacao');
                                return $q->where('observacao', 'like', "%$observacao%");
                            }
                        );
                        $query->when(
                            isset($consultaData['doenca']),
                            function ($q) {
                                $doenca = request('doenca');
                                return $q->where('doenca', 'like', "%$doenca%");
                            }
                        );
                        $query->when(
                            isset($consultaData['recomendacao']),
                            function ($q) {
                                $recomendacao = request('recomendacao');
                                return $q->where('recomendacao', 'like', "%$recomendacao%");
                            }
                        );
                        $query->when(
                            isset($consultaData['valor_cobrado']),
                            function ($q) {
                                $valor_cobrado = request('valor_cobrado');
                                return $q->where('valor_cobrado', 'like', "%$valor_cobrado%");
                            }
                        );
                        $query->when(
                            isset($consultaData['created_at']),
                            function ($q) {
                                $created_at = request('created_at');
                                return $q->whereDate('created_at',  $created_at);
                            }
                        );
                        $query->when(
                            isset($consultaData['updated_at']),
                            function ($q) {
                                $updated_at = request('updated_at');
                                return $q->whereDate('updated_at',  $updated_at);
                            }
                        );
                        $query->orderByDesc('created_at');
                        $consultas_encontradas = $query->get();

                        /*
                AQUI EM BAIXO NESSE FOREACH ERA UMA TENTATIVA DE 
                LER O REQUEST E COLOCAR CADA PARAMETRO AUTOMATICAMENTE
                NA QUERY, MAS DECIDI FAZER CADA UMA SEPARADA PARA MAPEAR CERTO
                QUAIS OS PARAMETROS E OS CAMPOS DA CONSULTA, EVITANDO ASSIM BUGS.
                */
                        // foreach ($consultaData as $key => $value) {
                        //     $query->when(
                        //         isset($consultaData[$key]),
                        //         function ($q, $value, $key) {
                        //             return $q->where($key, 'like', "%$value%");
                        //         }
                        //     );
                        // }

                        if ($consultas_encontradas->isEmpty()) {
                            return response()->json(['data' => ["msg" => 'Nenhuma consulta encontrada']], 404);
                        }
                        return response()->json(['data' => $consultas_encontradas], 200);
                    }
                    return \response()->json(["data" => ['msg' => "Falta um id do animal!"]], 422);
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

    public function criar(Request $request)
    {
        try {
            $consultaData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $this->consulta->create($consultaData);
                    return response()->json(['data' => ['msg' => "Consulta criada com sucesso"]], 201);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if ($e->getCode() == 'HY000') {
                return response()->json(["data" => ["msg" => "Faltou o id do animal", "code" => 1010]], 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == '22007') {
                return response()->json(["data" => ["msg" => "Algum campo esta incorreto", "code" => 1010]], 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function alterar(Request $request)
    {
        try {
            $consultaData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $consulta_encontrada = $this->consulta->find($consultaData['id']);
                    if (isset($consulta_encontrada)) {
                        $consulta_encontrada->update($consultaData);
                        return response()->json(['data' => ['msg' => "Consulta alterado com sucesso"]], 201);
                    }
                    return response()->json(ApiError::errorMessage("Consulta de id $consultaData[id] nao encontrado", 404), 404);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if ($e->getCode() == 'HY000') {
                return response()->json(["data" => ["msg" => "Faltou o id do animal", "code" => 1010]], 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 22007) {
                return response()->json(["data" => ["msg" => "Algum campo esta incorreto", "code" => 1010]], 422);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function deletar(Request $request)
    {
        try {
            // dd($request->all());
            $consultaData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $consulta_encontrada = $this->consulta->find(request('id'));
                    if (isset($consulta_encontrada)) {
                        $consulta_encontrada->delete($consultaData);
                        return response()->json(['data' => ['msg' => "Consulta " . $consultaData['id'] . " deletada com sucesso"]], 200);
                    }
                    return response()->json(ApiError::errorMessage("Consulta de id $consultaData[id] nao encontrado", 404), 404);
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
