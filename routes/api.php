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
|
*/

/**
 * Anotações aula 287 sobre api
 *
 * Quando falamos de api rest, nós retornamos um content-type do tipo aplication/json, ao contrário
 * de quando estamos falando de rotas web, onde o conteúdo retornado é do tipo text/html
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Aula 287
 *
 * Se retornarmos um array associativo, automaticamente o laravel sabe que precisa retornar essa informação
 * no formato json, isso pode ser visto no header da resposta no navegador, no atributo content-type
 */
Route::get('/', function () {
    return ['Chegamos ate aqui' => 'SIM'];
});

/**
 * Aula 288
 *
 * Na versão 8 o laravel está com um bug, até que seja resolvido, utilizar o namespace completo para indicar o controlador
 *
 * Quando estamos trabalhando com api rest, não implementamos os métodos de create e edit, pois não trafegamos conteúdo html,
 * portanto devemos usar a instrução apiResource. Podemos remover os métodos cretae e delete dos controladores
 */
// Route::resource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('carro', 'App\Http\Controllers\CarroController');
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');

/**
 * Aula 289 - extra para corrigir o problema do namespace completo
 *
 * Para isso basta fazer um ajuste de configuração, indo em app/providers/RouteServiceProvider e descomentando a instrução
 * relacionada ao namespace
 */



