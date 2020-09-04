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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace("API")->group(function () {
    Route::prefix('usuarios')->group(function () {
        Route::get("/login", 'UsuariosController@login')->name('login_usuario');
        Route::get('/{id}', 'UsuariosController@show')->name('unico_usuario');
        Route::get('/', 'UsuariosController@index')->name('index_usuarios');
        Route::post('/', 'UsuariosController@criar')->name('criar_usuarios');
        Route::put('/', 'UsuariosController@alterar')->name('alterar_usuarios');
        Route::delete('/', 'UsuariosController@deletar')->name('deletar_usuarios');
    });
    // Route::prefix('clientes')->group(function () {
    //     Route::get('/', 'UsuariosController@index')->name('index_usuarios');
    //     Route::get('/{id}', 'UsuariosController@show')->name('unico_usuario');
    //     Route::post('/', 'UsuariosController@criar')->name('criar_usuarios');
    //     Route::put('/', 'UsuariosController@alterar')->name('alterar_usuarios');
    //     Route::delete('/', 'UsuariosController@deletar')->name('deletar_usuarios');
    // });
});

Route::get('/ok', function () {
    return ['status' => true];
});
