<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return "Cache is cleared";
});

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/logout', 'Auth\LoginController@logout');

Route::group(["prefix" => "profile"], function(){
	Route::get('/', 'UserController@profile');
	Route::post('/store', 'UserController@saveProfile');
});



// Roles
Route::group(["prefix" => "roles"], function(){
	Route::get('/', 'RolesController@index');
	
	Route::get('/crear', 'RolesController@create');
	Route::post('/store', 'RolesController@store');
	
	Route::get('/edit/{id}', 'RolesController@edit');
	Route::post('/update/{id}', 'RolesController@update');
	
	Route::get('/delete/{id}', 'RolesController@destroy');
});


// Usuarios
Route::group(["prefix" => "usuarios"], function(){
	Route::get('/', 'UserController@index');
	
	Route::get('/crear', 'UserController@create');
	Route::post('/store', 'UserController@store');
	
	Route::get('/edit/{id}', 'UserController@edit');
	Route::post('/update/{id}', 'UserController@update');
	
	Route::get('/delete/{id}', 'UserController@destroy');
});


// Parámetros
Route::group(["prefix" => "parametros"], function(){
	Route::get('/', 'ParametrosController@index');
	
	Route::get('/crear', 'ParametrosController@create');
	Route::post('/store', 'ParametrosController@store');
	
	Route::get('/edit/{id}', 'ParametrosController@edit');
	Route::post('/update/{id}', 'ParametrosController@update');
	
	Route::get('/delete/{id}', 'ParametrosController@destroy');
});


// Demandantes
Route::group(["prefix" => "demandantes"], function(){
	Route::get('/', 'DemandantesController@index');
	
	Route::get('/crear', 'DemandantesController@create');
	Route::post('/store', 'DemandantesController@store');
	
	Route::get('/edit/{id}', 'DemandantesController@edit');
	Route::post('/update/{id}', 'DemandantesController@update');
	
	Route::get('/delete/{id}', 'DemandantesController@destroy');
});


// Pagadurias
Route::group(["prefix" => "pagadurias"], function(){
	Route::get('/', 'PagaduriasController@index');
	
	Route::get('/crear', 'PagaduriasController@create');
	Route::post('/store', 'PagaduriasController@store');
	
	Route::get('/edit/{id}', 'PagaduriasController@edit');
	Route::post('/update/{id}', 'PagaduriasController@update');
	
	Route::get('/delete/{id}', 'PagaduriasController@destroy');
});


// Aliados
Route::group(["prefix" => "aliados"], function(){
	Route::get('/', 'AliadosController@index');
	
	Route::get('/crear', 'AliadosController@create');
	Route::post('/store', 'AliadosController@store');
	
	Route::get('/edit/{id}', 'AliadosController@edit');
	Route::post('/update/{id}', 'AliadosController@update');
	
	Route::get('/delete/{id}', 'AliadosController@destroy');
	
	Route::get('/parametrizar', 'AliadosvalidosController@index');
});


// Aliados Validos
Route::group(["prefix" => "aliadosvalidos"], function(){
	Route::get('/', 'AliadosvalidosController@index');
	
	Route::get('/crear', 'AliadosvalidosController@create');
	Route::post('/store', 'AliadosvalidosController@store');
	
	Route::get('/edit/{id}', 'AliadosvalidosController@edit');
	Route::post('/update/{id}', 'AliadosvalidosController@update');
	
	Route::get('/delete/{id}', 'AliadosvalidosController@destroy');	
});


// Entidades
Route::group(["prefix" => "entidades"], function(){
	Route::get('/', 'EntidadesController@index');
	
	Route::get('/crear', 'EntidadesController@create');
	Route::post('/store', 'EntidadesController@store');
	
	Route::get('/edit/{id}', 'EntidadesController@edit');
	Route::post('/update/{id}', 'EntidadesController@update');
	
	Route::get('/delete/{id}', 'EntidadesController@destroy');
});


// Estados Cartera
Route::group(["prefix" => "estadoscartera"], function(){
	Route::get('/', 'EstadoscarteraController@index');
	
	Route::get('/crear', 'EstadoscarteraController@create');
	Route::post('/store', 'EstadoscarteraController@store');
	
	Route::get('/edit/{id}', 'EstadoscarteraController@edit');
	Route::post('/update/{id}', 'EstadoscarteraController@update');
	
	Route::get('/delete/{id}', 'EstadoscarteraController@destroy');
});


// Tipos de Embargo
Route::group(["prefix" => "tiposembargo"], function(){
	Route::get('/', 'TiposembargoController@index');
	
	Route::get('/crear', 'TiposembargoController@create');
	Route::post('/store', 'TiposembargoController@store');
	
	Route::get('/edit/{id}', 'TiposembargoController@edit');
	Route::post('/update/{id}', 'TiposembargoController@update');
	
	Route::get('/delete/{id}', 'TiposembargoController@destroy');
});


// Sectores
Route::group(["prefix" => "sectores"], function(){
	Route::get('/', 'SectoresController@index');
	
	Route::get('/crear', 'SectoresController@create');
	Route::post('/store', 'SectoresController@store');
	
	Route::get('/edit/{id}', 'SectoresController@edit');
	Route::post('/update/{id}', 'SectoresController@update');
	
	Route::get('/delete/{id}', 'SectoresController@destroy');
});


// Oficinas
Route::group(["prefix" => "oficinas"], function(){
	Route::get('/', 'OficinasController@index');
	
	Route::get('/crear', 'OficinasController@create');
	Route::post('/store', 'OficinasController@store');
	
	Route::get('/edit/{id}', 'OficinasController@edit');
	Route::post('/update/{id}', 'OficinasController@update');
	
	Route::get('/delete/{id}', 'OficinasController@destroy');
});


// Cargos
Route::group(["prefix" => "cargos"], function(){
	Route::get('/', 'CargosController@index');
	
	Route::get('/crear', 'CargosController@create');
	Route::post('/store', 'CargosController@store');
	
	Route::get('/edit/{id}', 'CargosController@edit');
	Route::post('/update/{id}', 'CargosController@update');
	
	Route::get('/delete/{id}', 'CargosController@destroy');
});


// Departamentos
Route::group(["prefix" => "departamentos"], function(){
	Route::get('/', 'DepartamentosController@index');
	
	Route::get('/crear', 'DepartamentosController@create');
	Route::post('/store', 'DepartamentosController@store');
	
	Route::get('/edit/{id}', 'DepartamentosController@edit');
	Route::post('/update/{id}', 'DepartamentosController@update');
	
	Route::get('/delete/{id}', 'DepartamentosController@destroy');
});


// Ciudades
Route::group(["prefix" => "ciudades"], function(){
	Route::get('/', 'CiudadesController@index');
	
	Route::get('/crear', 'CiudadesController@create');
	Route::post('/store', 'CiudadesController@store');
	
	Route::get('/edit/{id}', 'CiudadesController@edit');
	Route::post('/update/{id}', 'CiudadesController@update');
	
	Route::get('/delete/{id}', 'CiudadesController@destroy');
});


// Estudios de clientes
Route::group(["prefix" => "terecuperamos"], function(){
	Route::get('/', 'TerecuperamosController@index');
	
	Route::get('/crear', 'TerecuperamosController@create');
	Route::post('/store', 'TerecuperamosController@store');
	
	Route::get('/edit/{id}', 'TerecuperamosController@edit');
	Route::post('/update/{id}', 'TerecuperamosController@update');
	
	Route::get('/delete/{id}', 'TerecuperamosController@destroy');
	
	Route::post('/saveObservaciones/{id}', 'TerecuperamosController@saveObservaciones');
});


// Comerciales
Route::group(["prefix" => "comerciales"], function(){
	Route::get('/', 'ComercialController@index');
	
	Route::get('/crear', 'ComercialController@create');
	Route::post('/store', 'ComercialController@store');
	
	Route::get('/edit/{id}', 'ComercialController@edit');
	Route::post('/update/{id}', 'ComercialController@update');
	
	Route::get('/delete/{id}', 'ComercialController@destroy');
});


// Factores
Route::group(["prefix" => "factores"], function(){
	Route::get('/', 'FactoresController@index');
	
	Route::get('/crear', 'FactoresController@create');
	Route::post('/store', 'FactoresController@store');
	
	Route::get('/edit/{id}', 'FactoresController@edit');
	Route::post('/update/{id}', 'FactoresController@update');
	
	Route::get('/delete/{id}', 'FactoresController@destroy');
});


// Reportes
Route::group(["prefix" => "reportes"], function(){
	Route::get('/', 'ReportesController@index');
	Route::get('/consultas', 'ReportesController@consultas');
	Route::get('/personalizados', 'ReportesController@personalizados');
	
});



// Archivos Planos
Route::group(["prefix" => "planos"], function(){
	Route::get('/', 'PlanosController@index');
	
	Route::get('/crear', 'PlanosController@create');
	Route::post('/store', 'PlanosController@store');
	
	Route::get('/edit/{id}', 'PlanosController@edit');
	Route::post('/update/{id}', 'PlanosController@update');
	
	Route::get('/delete/{id}', 'PlanosController@destroy');
});

// Consultas
Route::group(["prefix" => "consultas"], function(){
	Route::get('/', 'ConsultasController@index');

	Route::post('/{id}', 'ConsultasController@consultar');
});

// Estudios
Route::group(["prefix" => "estudios"], function(){

	Route::get('/', 'EstudiosController@index');

	Route::get('/nuevoestudio', 'EstudiosController@paso1');
	Route::post('/iniciar', 'EstudiosController@iniciar');
	Route::post('/guardar', 'EstudiosController@guardar');

	Route::get('/editar/{id}', 'EstudiosController@editar');
	Route::post('/actualizar', 'EstudiosController@actualizar');

	Route::get('/borrar/{id}', 'EstudiosController@eliminar');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dataset', 'DatasetController@index');
Route::get('/dataset/get', 'DatasetController@get');