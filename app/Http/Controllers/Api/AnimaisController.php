<?php

namespace App\Http\Controllers\Api;

use App\Animais;
use App\API\ApiError;
use App\API\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimaisController extends Controller
{
    public function __construct(Animais $animal)
    {
        $this->animal = $animal;
    }


    public function index()
    {
        // $data = ['data' => $this->animal->paginate(10)];
        // $data = ['data' => $this->animal->all()];
        // $data = ['data' => "Coloque na URL o id do animal! Ex.: /api/animais/25"];
        $data = ['data' => 'Você precisa estar logado!'];
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

    public function show(Animais $id)
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

    public function listar_animais(Request $request)
    {
        /*
        Esse listar_animais deve receber o id do cliente, 
        e ele vai retornar todos os animais
        vinculados a esse cliente
        No final das contas ele tem o mesmo resultado do buscar_animais
        quando não passa nenhum parametro, apenas muda a performance
        */
        try {
            $animalData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    if (isset($animalData['cliente_id'])) {
                        $animais_encontrados = Animais::where('cliente_id', request('cliente_id'))
                            ->orderBy('nome_animal')
                            ->get();
                        if (!$animais_encontrados->isEmpty()) {
                            return response()->json(['data' => $animais_encontrados], 200);
                        }
                        return response()->json(['data' => 'Nenhum animal encontrado'], 200);
                    }
                    return response()->json(['data' => ['msg' => 'Falta um id do cliente']], 422);
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

    public function buscar_animal(Request $request)
    {
        try {
            $animalData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    if (isset($animalData['cliente_id'])) {
                        //construindo a query
                        $query = Animais::query();
                        $query->where('cliente_id', request('cliente_id'));
                        $query->when(
                            isset($animalData['nome_animal']),
                            function ($q) {
                                $nome_animal = request('nome_animal');
                                return $q->where('nome_animal', 'like', "%$nome_animal%");
                            }
                        );
                        $query->when(
                            isset($animalData['dt_nasc']),
                            function ($q) {
                                $dt_nasc = request('dt_nasc');
                                return $q->whereDate('dt_nasc', $dt_nasc);
                            }
                        );
                        $query->when(
                            isset($animalData['observacao']),
                            function ($q) {
                                $observacao = request('observacao');
                                return $q->where('observacao', 'like', "%$observacao%");
                            }
                        );
                        $query->when(
                            isset($animalData['microchip']),
                            function ($q) {
                                $microchip = request('microchip');
                                return $q->where('microchip', 'like', "%$microchip%");
                            }
                        );
                        $query->when(
                            isset($animalData['tag']),
                            function ($q) {
                                $tag = request('tag');
                                return $q->where('tag', 'like', "%$tag%");
                            }
                        );
                        $query->when(
                            isset($animalData['sexo']),
                            function ($q) {
                                $sexo = request('sexo');
                                return $q->where('sexo', 'like', $sexo);
                            }
                        );
                        $query->when(
                            isset($animalData['castrado']),
                            function ($q) {
                                $castrado = request('castrado');
                                return $q->where('castrado', $castrado);
                            }
                        );
                        $query->when(
                            isset($animalData['cor']),
                            function ($q) {
                                $cor = request('cor');
                                return $q->where('cor', $cor);
                            }
                        );
                        $query->when(
                            isset($animalData['created_at']),
                            function ($q) {
                                $created_at = request('created_at');
                                return $q->whereDate('created_at',  $created_at);
                            }
                        );
                        $query->when(
                            isset($animalData['updated_at']),
                            function ($q) {
                                $updated_at = request('updated_at');
                                return $q->whereDate('updated_at',  $updated_at);
                            }
                        );
                        $query->orderBy('nome_animal');
                        $animais_encontrados = $query->get();

                        /*
                AQUI EM BAIXO NESSE FOREACH ERA UMA TENTATIVA DE 
                LER O REQUEST E COLOCAR CADA PARAMETRO AUTOMATICAMENTE
                NA QUERY, MAS DECIDI FAZER CADA UMA SEPARADA PARA MAPEAR CERTO
                QUAIS OS PARAMETROS E OS CAMPOS DA CONSULTA, EVITANDO ASSIM BUGS.
                */
                        // foreach ($animalData as $key => $value) {
                        //     $query->when(
                        //         isset($animalData[$key]),
                        //         function ($q, $value, $key) {
                        //             return $q->where($key, 'like', "%$value%");
                        //         }
                        //     );
                        // }

                        if ($animais_encontrados->isEmpty()) {
                            return response()->json(['data' => 'Nenhum animal encontrado'], 404);
                        }
                        return response()->json(['data' => $animais_encontrados], 200);
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

    public function criar(Request $request)
    {
        try {
            $animalData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $foto_animal = $request->file('foto_animal');
                    if ($foto_animal) {
                        if ($foto_animal->isValid()) {
                            // $result = Storage::disk('s3')->put("imagens", $foto_animal, 'public');
                            $result = $foto_animal->store('imagens', 's3');
                            //Ver aqui e fazer upar as imagens de froma publica
                            $animalData['caminho_foto'] = "https://appvet.s3-sa-east-1.amazonaws.com/" . $result;
                        } else {
                            return response()->json(['data' => ['msg' => ApiMessages::message(14)]], 400);
                        }
                    }
                    $this->animal->create($animalData);
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
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function alterar(Request $request)
    {
        try {
            $animalData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $animal_encontrado = $this->animal->find($animalData['id']);
                    if (isset($animal_encontrado)) {
                        $animal_encontrado->update($animalData);
                        return response()->json(['data' => ['msg' => ApiMessages::message(9)]], 201);
                    }
                    return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Animal"), 404), 404);
                }
            }
            return response()->json(["data" => ["msg" => ApiMessages::message(13)]], 422);
        } catch (\Exception $e) {
            if ($e->getCode() == 'HY000') {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            if ($e->getCode() == 22007) {
                return response()->json(["data" => ["msg" => ApiMessages::message(8), "code" => 1010]], 422);
            }
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function deletar(Request $request)
    {
        /*
        Ao deletar um animal, ele deletará todo o conteudo relacionado a esse cliente
        como por exemplo as consultas referentes a esse animal
        */
        try {
            // dd($request->all());
            $animalData = $request->all();
            if (isset($animalData['token'])) {
                if ($this->valida_token(request('token'))) {
                    $animal_encontrado = $this->animal->find(request('id'));
                    if (isset($animal_encontrado)) {
                        $animal_encontrado->delete($animalData);
                        return response()->json(['data' => ['msg' =>  ApiMessages::message(11)]], 200);
                    }
                    return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Animal"), 404), 404);
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
