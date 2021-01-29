<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Aliados
Route::group(['prefix' => 'aliados'], function(){
    Route::post('/show', 'JsonAliadosController@show');
    Route::post('/getFactor', 'JsonAliadosController@getFactor');
});

// Cargos
Route::group(['prefix' => 'cargos'], function(){
    Route::post('/xVinculacion', 'JsonCargosController@xVinculacion');
    Route::post('/xCargo', 'JsonCargosController@xCargo');
});

// Ciudades
Route::group(['prefix' => 'ciudades'], function(){
    Route::post('/xDepto', 'JsonCiudadesController@xDepto');
});

// Oficinas
Route::group(['prefix' => 'oficinas'], function(){
    Route::post('/xCiudad', 'JsonOficinasController@xCiudad');
});

// Comerciales
Route::group(['prefix' => 'comerciales'], function(){
    Route::post('/infoCliente', 'JsonComercialesController@infoCliente');
});

// Parametros
Route::group(['prefix' => 'parametros'], function(){
    Route::post('/xVinculacion', 'JsonParametrosController@xVinculacion');
});

// Terecuperamos
Route::group(['prefix' => 'terecuperamos'], function(){
    Route::post('/calcularDecision', 'JsonTerecuperamosController@calcularDecision');
    Route::post('/paso1', 'JsonTerecuperamosController@paso1');
    Route::post('/paso2', 'JsonTerecuperamosController@paso2');
    Route::post('/paso3', 'JsonTerecuperamosController@paso3');
    Route::post('/paso4', 'JsonTerecuperamosController@paso4');
    Route::post('/paso5', 'JsonTerecuperamosController@paso5');
    Route::post('/paso6', 'JsonTerecuperamosController@paso6');
    Route::post('/paso7', 'JsonTerecuperamosController@paso7');
});


// Carteras
Route::group(['prefix' => 'carteras'], function(){
    Route::post('/total', 'JsonCarterasController@total');
    Route::post('/show', 'JsonCarterasController@show');
    Route::post('/delete', 'JsonCarterasController@destroy');
    Route::post('/compraCk', 'JsonCarterasController@compraCk');
});


// Reportes
Route::group(['prefix' => 'reportes'], function(){
    Route::post('/consultas', 'JsonReportesController@consultas');
});

// Clientes
Route::group(['prefix' => 'clientes'], function(){
    Route::post('/getPagaduriasXPeriodo', 'JsonClientesController@getPagaduriasXPeriodo');
    Route::post('/getRegistrosXPagaduriayPeriodo', 'JsonClientesController@getRegistrosXPagaduriayPeriodo');
    Route::post('/actualizarRegistro', 'JsonClientesController@actualizarRegistro');
});