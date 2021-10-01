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
//Na aula 331 foram implementados os middlewares de autenticação jwt
Route::prefix('v1')->middleware('jwt.auth')->group( function() { //Aula 332 - este prefixo indica a versão da api, caso seja feita uma atualização substancial, através desse versionamento é possivel que aplicações antigas que utilizem a api consigam continuar utilizando
    Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
    Route::apiResource('carro', 'App\Http\Controllers\CarroController');
    Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
    Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
    Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');

    Route::post('me', 'App\Http\Controllers\AuthController@me'); // Aula 334 - a rota me precisa executar o meedleware jwt.auth
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh'); // Aula 335
    Route::post('logout', 'App\Http\Controllers\AuthController@logout'); // Aula 336
});

/**
 * Aula 289 - extra para corrigir o problema do namespace completo
 *
 * Para isso basta fazer um ajuste de configuração, indo em app/providers/RouteServiceProvider e descomentando a instrução
 * relacionada ao namespace
 */

 //Aula 328 - autenticação em api
 Route::post('login', 'App\Http\Controllers\AuthController@login');





