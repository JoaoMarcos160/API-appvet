<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::namespace("Api")->group(function () {
    Route::prefix("usuarios")->group(function () {
        Route::get("/{id}", "UsuariosController@show")->name("unico_usuario");
        Route::get("/", "UsuariosController@index")->name("index_usuarios");
        Route::post("/login", "UsuariosController@login")->name("login_usuario");
        Route::post("/", "UsuariosController@criar")->name("criar_usuarios");
        Route::put("/", "UsuariosController@alterar")->name("alterar_usuarios");
        Route::delete("/", "UsuariosController@deletar")->name("deletar_usuarios");
    });
    Route::prefix("clientes")->group(function () {
        Route::get("/{id}", "ClientesController@show")->name("unico_cliente");
        Route::get("/", "ClientesController@index")->name("index_clientes");
        Route::post("/listar", "ClientesController@listar_clientes")->name("listar_clientes");
        Route::post("/buscar", "ClientesController@buscar_cliente")->name("buscar_cliente");
        Route::post("/", "ClientesController@criar")->name("criar_clientes");
        Route::put("/", "ClientesController@alterar")->name("alterar_clientes");
        Route::delete("/", "ClientesController@deletar")->name("deletar_clientes");
    });
    Route::prefix("animais")->group(function () {
        Route::get("/{id}", "AnimaisController@show")->name("unico_animais");
        Route::get("/", "AnimaisController@index")->name("index_animais");
        Route::post("/listar", "AnimaisController@listar_animais")->name("listar_animais");
        Route::post("/buscar", "AnimaisController@buscar_animal")->name("buscar_animal");
        Route::post("/", "AnimaisController@criar")->name("criar_animais");
        Route::put("/", "AnimaisController@alterar")->name("alterar_animais");
        Route::delete("/", "AnimaisController@deletar")->name("deletar_animais");
    });
    Route::prefix("consultas")->group(function () {
        Route::get("/{id}", "ConsultasController@show")->name("unico_consultas");
        Route::get("/", "ConsultasController@index")->name("index_consultas");
        Route::post("/listar", "ConsultasController@listar_consultas")->name("listar_consultas");
        Route::post("/buscar", "ConsultasController@buscar_consulta")->name("buscar_consulta");
        Route::post("/", "ConsultasController@criar")->name("criar_consultas");
        Route::put("/", "ConsultasController@alterar")->name("alterar_consultas");
        Route::delete("/", "ConsultasController@deletar")->name("deletar_consultas");
    });
    Route::prefix("ok")->group(function () {
        Route::get("/", function () {
            //função só pra conferir se a api está online
            return response()->json(["status" => true]);
        })->name("ok");
    });
});
