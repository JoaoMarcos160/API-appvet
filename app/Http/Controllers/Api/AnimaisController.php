<?php

namespace App\Http\Controllers\Api;

use App\Animais;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnimaisController extends Controller
{
    public function __construct(Animais $animal)
    {
        $this->animal = $animal;
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

    public function show(Animais $id)
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

    
}
