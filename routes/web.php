<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\AreaComercialController;
use App\Http\Controllers\ColpensionesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DecevalController;
use App\Http\Controllers\DemograficoController;
use App\Http\Controllers\DescuentosController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmbargosController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FiducidiariaController;
use App\Http\Controllers\FileUploadLogController;
use App\Http\Controllers\JelouController;
use App\Http\Controllers\JoinPensionesController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\VisadoController;
use App\Http\Controllers\ParametrosComparativaController;
use App\Http\Controllers\Admin\DataCoverageController;
use App\Http\Controllers\Fintra\CreditRequestController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
| En este archivo se registran las rutas web de la aplicación, cargadas
| por RouteServiceProvider dentro de un grupo que contiene el middleware
| "web".
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------------------
// AUTENTICACIÓN BÁSICA
// ------------------------------------------------------------------
Auth::routes(['register' => true]);

// Rutas Home
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/ami', 'HomeController@ami')->name('ami');
Route::get('/logout', 'Auth\LoginController@logout');

// ------------------------------------------------------------------
// PERFIL DE USUARIO
// ------------------------------------------------------------------
Route::group(['prefix' => 'profile'], function () {
    Route::get('/', 'ProfileController@profile');
    Route::post('/store', 'ProfileController@saveProfile');
});

// ------------------------------------------------------------------
// ROLES
// ------------------------------------------------------------------
Route::group(['prefix' => 'roles'], function () {
    Route::get('/', 'RolesController@index');
    Route::get('/crear', 'RolesController@create');
    Route::post('/store', 'RolesController@store');
    Route::get('/edit/{id}', 'RolesController@edit');
    Route::post('/update/{id}', 'RolesController@update');
    Route::get('/delete/{id}', 'RolesController@destroy');
});

// ------------------------------------------------------------------
// USUARIOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'usuarios'], function () {
    Route::get('/', 'UserController@index');
    Route::get('/crear', 'UserController@create');
    Route::post('/store', 'UserController@store');
    Route::get('/edit/{id}', 'UserController@edit');
    Route::post('/update/{id}', 'UserController@update');
    Route::get('/delete/{id}', 'UserController@destroy');
});

// ------------------------------------------------------------------
// PARÁMETROS
// ------------------------------------------------------------------
Route::group(['prefix' => 'parametros'], function () {
    Route::get('/', 'ParametrosController@index');
    Route::get('/crear', 'ParametrosController@create');
    Route::post('/store', 'ParametrosController@store');
    Route::get('/edit/{id}', 'ParametrosController@edit');
    Route::post('/update/{id}', 'ParametrosController@update');
    Route::get('/delete/{id}', 'ParametrosController@destroy');
});

// ------------------------------------------------------------------
// FACTORES X MILLÓN KREDIT
// ------------------------------------------------------------------
Route::group(['prefix' => 'factorxmillonkredit'], function () {
    Route::get('/', 'FactoresXMillonKreditController@index');
    Route::get('/crear', 'FactoresXMillonKreditController@create');
    Route::post('/store', 'FactoresXMillonKreditController@store');
    Route::get('/edit/{id}', 'FactoresXMillonKreditController@edit');
    Route::post('/update/{id}', 'FactoresXMillonKreditController@update');
    Route::get('/delete/{id}', 'FactoresXMillonKreditController@destroy');
});

// ------------------------------------------------------------------
// DEMANDANTES
// ------------------------------------------------------------------
Route::group(['prefix' => 'demandantes'], function () {
    Route::get('/', 'DemandantesController@index');
    Route::get('/crear', 'DemandantesController@create');
    Route::post('/store', 'DemandantesController@store');
    Route::get('/edit/{id}', 'DemandantesController@edit');
    Route::post('/update/{id}', 'DemandantesController@update');
    Route::get('/delete/{id}', 'DemandantesController@destroy');
});

// ------------------------------------------------------------------
// PAGADURÍAS
// ------------------------------------------------------------------
Route::group(['prefix' => 'pagadurias'], function () {
    Route::get('/', 'PagaduriasController@index');
    Route::get('/crear', 'PagaduriasController@create');
    Route::post('/store', 'PagaduriasController@store');
    Route::get('/edit/{id}', 'PagaduriasController@edit');
    Route::post('/update/{id}', 'PagaduriasController@update');
    Route::get('/delete/{id}', 'PagaduriasController@destroy');

    Route::get('/per-doc/{doc}', 'PagaduriasController@perDoc');
    Route::get('/per-doc-express/{doc}', 'PagaduriasController@perDocExpress');
});

// ------------------------------------------------------------------
// PAGOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'pagos'], function () {
    Route::get('/', 'PagosController@index');
    Route::get('/pagar', 'PagosController@pagar');
    Route::get('/pagarpse', 'PagosController@pagarpse');
    Route::post('/pay', 'PagosController@pay');
    Route::post('/payPSE', 'PagosController@payPSE');
    Route::get('/payPSE', 'PagosController@getPayPSE');
    Route::post('/pagarefectivo', 'PagosController@payEfectivo');
    Route::get('/pagarefectivo', 'PagosController@getPayEfectivo');

    // Nuevas (no estaban en el primer bloque, se conservan)
    Route::get('/edit/{id}', 'PagosController@edit');
    Route::post('/update/{id}', 'PagosController@update');
    Route::get('/delete/{id}', 'PagosController@destroy');
});

// ------------------------------------------------------------------
// CIFIN
// ------------------------------------------------------------------
Route::group(['prefix' => 'cifin'], function () {
    Route::get('/', 'CifinController@index');
    Route::get('/consultar', 'CifinController@consultar')->name('cifin.consultar');
    Route::get('/consulta', 'CifinController@consulta');
    Route::post('/consultarAdmin', 'CifinController@consultarAdmin')->name('cifin.consultarAdmin');
});

// ------------------------------------------------------------------
// DATACRÉDITO
// ------------------------------------------------------------------
Route::group(['prefix' => 'datacredito'], function () {
    Route::get('/', 'DataCreditoController@index');
    Route::get('/consultar', 'DataCreditoController@consultar')->name('datacredito.consultar');
    Route::get('/consulta', 'DataCreditoController@consulta');
});

// ------------------------------------------------------------------
// DECEVAL
// ------------------------------------------------------------------
Route::group(['prefix' => 'deceval'], function () {
    Route::get('/', 'DecevalController@index');
    Route::get('/consultar', 'DecevalController@consultar')->name('deceval.consultar');
    Route::post('/firmar', 'DecevalController@firmar');
    Route::get('/consulta', 'DecevalController@consulta');
    Route::get('/test', [DecevalController::class, 'testService']);

    // Repeticiones para BBVA - las conservamos solo una vez
    Route::post('/pagarpse', 'PagosController@pagarpse');
    Route::post('/pay', 'PagosController@pay');
    Route::post('/payPSE', 'PagosController@payPSE');
    Route::get('/payPSE', 'PagosController@getPayPSE');
    Route::get('/edit/{id}', 'PagosController@edit');
    Route::post('/update/{id}', 'PagosController@update');
    Route::get('/delete/{id}', 'PagosController@destroy');
});

// ------------------------------------------------------------------
// ALIADOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'aliados'], function () {
    Route::get('/', 'AliadosController@index');
    Route::get('/crear', 'AliadosController@create');
    Route::post('/store', 'AliadosController@store');
    Route::get('/edit/{id}', 'AliadosController@edit');
    Route::post('/update/{id}', 'AliadosController@update');
    Route::get('/delete/{id}', 'AliadosController@destroy');

    Route::get('/parametrizar', 'AliadosvalidosController@index');
});

// ------------------------------------------------------------------
// ALIADOS VÁLIDOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'aliadosvalidos'], function () {
    Route::get('/', 'AliadosvalidosController@index');
    Route::get('/crear', 'AliadosvalidosController@create');
    Route::post('/store', 'AliadosvalidosController@store');
    Route::get('/edit/{id}', 'AliadosvalidosController@edit');
    Route::post('/update/{id}', 'AliadosvalidosController@update');
    Route::get('/delete/{id}', 'AliadosvalidosController@destroy');
});

// ------------------------------------------------------------------
// ENTIDADES
// ------------------------------------------------------------------
Route::group(['prefix' => 'entidades'], function () {
    Route::get('/', 'EntidadesController@index');
    Route::get('/crear', 'EntidadesController@create');
    Route::post('/store', 'EntidadesController@store');
    Route::get('/edit/{id}', 'EntidadesController@edit');
    Route::post('/update/{id}', 'EntidadesController@update');
    Route::get('/delete/{id}', 'EntidadesController@destroy');
});

// ------------------------------------------------------------------
// ESTADOS CARTERA
// ------------------------------------------------------------------
Route::group(['prefix' => 'estadoscartera'], function () {
    Route::get('/', 'EstadoscarteraController@index');
    Route::get('/crear', 'EstadoscarteraController@create');
    Route::post('/store', 'EstadoscarteraController@store');
    Route::get('/edit/{id}', 'EstadoscarteraController@edit');
    Route::post('/update/{id}', 'EstadoscarteraController@update');
    Route::get('/delete/{id}', 'EstadoscarteraController@destroy');
});

// ------------------------------------------------------------------
// TIPOS DE EMBARGO
// ------------------------------------------------------------------
Route::group(['prefix' => 'tiposembargo'], function () {
    Route::get('/', 'TiposembargoController@index');
    Route::get('/crear', 'TiposembargoController@create');
    Route::post('/store', 'TiposembargoController@store');
    Route::get('/edit/{id}', 'TiposembargoController@edit');
    Route::post('/update/{id}', 'TiposembargoController@update');
    Route::get('/delete/{id}', 'TiposembargoController@destroy');
});

// ------------------------------------------------------------------
// SECTORES
// ------------------------------------------------------------------
Route::group(['prefix' => 'sectores'], function () {
    Route::get('/', 'SectoresController@index');
    Route::get('/crear', 'SectoresController@create');
    Route::post('/store', 'SectoresController@store');
    Route::get('/edit/{id}', 'SectoresController@edit');
    Route::post('/update/{id}', 'SectoresController@update');
    Route::get('/delete/{id}', 'SectoresController@destroy');
});

// ------------------------------------------------------------------
// OFICINAS
// ------------------------------------------------------------------
Route::group(['prefix' => 'oficinas'], function () {
    Route::get('/', 'OficinasController@index');
    Route::get('/crear', 'OficinasController@create');
    Route::post('/store', 'OficinasController@store');
    Route::get('/edit/{id}', 'OficinasController@edit');
    Route::post('/update/{id}', 'OficinasController@update');
    Route::get('/delete/{id}', 'OficinasController@destroy');
});

// ------------------------------------------------------------------
// CARGOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'cargos'], function () {
    Route::get('/', 'CargosController@index');
    Route::get('/crear', 'CargosController@create');
    Route::post('/store', 'CargosController@store');
    Route::get('/edit/{id}', 'CargosController@edit');
    Route::post('/update/{id}', 'CargosController@update');
    Route::get('/delete/{id}', 'CargosController@destroy');
});

// ------------------------------------------------------------------
// DEPARTAMENTOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'departamentos'], function () {
    Route::get('/', 'DepartamentosController@index');
    Route::get('/crear', 'DepartamentosController@create');
    Route::post('/store', 'DepartamentosController@store');
    Route::get('/edit/{id}', 'DepartamentosController@edit');
    Route::post('/update/{id}', 'DepartamentosController@update');
    Route::get('/delete/{id}', 'DepartamentosController@destroy');
});

// ------------------------------------------------------------------
// CIUDADES
// ------------------------------------------------------------------
Route::group(['prefix' => 'ciudades'], function () {
    Route::get('/', 'CiudadesController@index');
    Route::get('/crear', 'CiudadesController@create');
    Route::post('/store', 'CiudadesController@store');
    Route::get('/edit/{id}', 'CiudadesController@edit');
    Route::post('/update/{id}', 'CiudadesController@update');
    Route::get('/delete/{id}', 'CiudadesController@destroy');
});

// ------------------------------------------------------------------
// TERECUPERAMOS (Estudios de clientes)
// ------------------------------------------------------------------
Route::group(['prefix' => 'terecuperamos'], function () {
    Route::get('/', 'TerecuperamosController@index');
    Route::get('/crear', 'TerecuperamosController@create');
    Route::post('/store', 'TerecuperamosController@store');
    Route::get('/edit/{id}', 'TerecuperamosController@edit');
    Route::post('/update/{id}', 'TerecuperamosController@update');
    Route::get('/delete/{id}', 'TerecuperamosController@destroy');
    Route::post('/saveObservaciones/{id}', 'TerecuperamosController@saveObservaciones');
});

// ------------------------------------------------------------------
// COMERCIALES
// ------------------------------------------------------------------
Route::group(['prefix' => 'comerciales'], function () {
    Route::get('/', 'ComercialController@index');
    Route::get('/crear', 'ComercialController@create');
    Route::post('/store', 'ComercialController@store');
    Route::get('/edit/{id}', 'ComercialController@edit');
    Route::post('/update/{id}', 'ComercialController@update');
    Route::get('/delete/{id}', 'ComercialController@destroy');
});

// ------------------------------------------------------------------
// FACTORES
// ------------------------------------------------------------------
Route::group(['prefix' => 'factores'], function () {
    Route::get('/', 'FactoresController@index');
    Route::get('/crear', 'FactoresController@create');
    Route::post('/store', 'FactoresController@store');
    Route::get('/edit/{id}', 'FactoresController@edit');
    Route::post('/update/{id}', 'FactoresController@update');
    Route::get('/delete/{id}', 'FactoresController@destroy');
});

// ------------------------------------------------------------------
// CUENTAS BANCARIAS
// ------------------------------------------------------------------
Route::group(['prefix' => 'cuentasbancarias'], function () {
    Route::get('/', 'CuentasBancariasController@index');
    Route::get('/crear', 'CuentasBancariasController@create');
    Route::post('/store', 'CuentasBancariasController@store');
    Route::get('/edit/{id}', 'CuentasBancariasController@edit');
    Route::post('/update/{id}', 'CuentasBancariasController@update');
    Route::get('/delete/{id}', 'CuentasBancariasController@destroy');
});

// ------------------------------------------------------------------
// ENTIDADES DESEMBOLSO
// ------------------------------------------------------------------
Route::group(['prefix' => 'entidadesdesembolso'], function () {
    Route::get('/', 'EntidadesDesembolsoController@index');
    Route::get('/crear', 'EntidadesDesembolsoController@create');
    Route::post('/store', 'EntidadesDesembolsoController@store');
    Route::get('/edit/{id}', 'EntidadesDesembolsoController@edit');
    Route::post('/update/{id}', 'EntidadesDesembolsoController@update');
    Route::get('/delete/{id}', 'EntidadesDesembolsoController@destroy');
});

// ------------------------------------------------------------------
// FORMA PAGO
// ------------------------------------------------------------------
Route::group(['prefix' => 'formapago'], function () {
    Route::get('/', 'FormaPagoController@index');
    Route::get('/crear', 'FormaPagoController@create');
    Route::post('/store', 'FormaPagoController@store');
    Route::get('/edit/{id}', 'FormaPagoController@edit');
    Route::post('/update/{id}', 'FormaPagoController@update');
    Route::get('/delete/{id}', 'FormaPagoController@destroy');
});

// ------------------------------------------------------------------
// TIPO GIRO
// ------------------------------------------------------------------
Route::group(['prefix' => 'tipogiro'], function () {
    Route::get('/', 'TipoGiroController@index');
    Route::get('/crear', 'TipoGiroController@create');
    Route::post('/store', 'TipoGiroController@store');
    Route::get('/edit/{id}', 'TipoGiroController@edit');
    Route::post('/update/{id}', 'TipoGiroController@update');
    Route::get('/delete/{id}', 'TipoGiroController@destroy');
});

// ------------------------------------------------------------------
// REPORTES
// ------------------------------------------------------------------
Route::group(['prefix' => 'reportes'], function () {
    Route::get('/', 'ReportesController@index');
    Route::get('/consultas', 'ReportesController@consultas');
    Route::get('/personalizados', 'ReportesController@personalizados');
});

// ------------------------------------------------------------------
// ARCHIVOS PLANOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'planos'], function () {
    Route::get('/', 'PlanosController@index');
    Route::get('/crear', 'PlanosController@create');
    Route::post('/store', 'PlanosController@store');
    Route::get('/crear_gcp', 'PlanosController@create_gcp');
    Route::get('/ver_gcp/{id}', 'PlanosController@ver_gcp')->name('ver_archivo');
    Route::post('/store_gcp_cedula', 'PlanosController@store_gcp_cedula');
    Route::post('/store_gcp_masivo', 'PlanosController@store_gcp_masivo');
    Route::get('/edit/{id}', 'PlanosController@edit');
    Route::post('/update/{id}', 'PlanosController@update');
    Route::get('/delete/{id}', 'PlanosController@destroy');
});

// ------------------------------------------------------------------
// CONSULTAS
// ------------------------------------------------------------------
Route::group(['prefix' => 'consultas'], function () {
    Route::get('/', 'ConsultasController@index');
    Route::get('/list', 'ConsultasController@list');
    Route::post('/{id}', 'ConsultasController@consultar');
});

// ------------------------------------------------------------------
// ESTUDIOS
// ------------------------------------------------------------------
Route::group(['prefix' => 'estudios'], function () {
    // Primer bloque
    Route::get('/', 'EstudiosController@index')->name('hego.estudios');
    Route::get('/nuevo-estudio', 'EstudiosController@paso1')->name('hego.nuevo-estudio');
    Route::post('/iniciar', 'EstudiosController@iniciar');
    Route::get('/iniciar/{documento}', 'EstudiosController@iniciar');
    Route::post('/guardar', 'EstudiosController@guardar');
    Route::post('/giros', 'TesoreriaController@agregarGiro');

    Route::get('/editar/{id}', 'EstudiosController@editar');
    Route::get('/editarSinCIFIN/{id}', 'EstudiosController@editarSinCIFIN');
    Route::post('/actualizar', 'EstudiosController@actualizar');
    Route::get('/borrar/{id}', 'EstudiosController@eliminar');

    Route::get('/tesoreria', 'TesoreriaController@index')->name('hego.tesoreria');
    Route::get('/tesoreria/detalle/{id}', 'TesoreriaController@detalleTesoreria')->name('tesoreria.detalle');

    Route::get('/cartera', 'EstudiosController@cartera')->name('hego.cartera');
    Route::get('/venta-cartera', 'EstudiosController@ventaCartera')->name('hego.venta-cartera');
    Route::get('/detalle-cartera/{id}/{tipoconsulta?}', 'CarteraController@detalleCateraView');
    Route::post('/comprar-cartera', 'EstudiosController@compraCartera');

    // Rutas adicionales del segundo bloque (no estaban en el primero)
    Route::post('/estudio-actualizar', 'EstudiosController@actualizarNew')->name('estudio.actualizar');
    Route::get('/pagos/{id}', 'EstudiosController@pagos')->name('estudio.pagos');
    Route::get('/recaudo/{id}', 'EstudiosController@recaudo')->name('estudio.recaudo');
    Route::post('/recaudos/guardar', 'EstudiosController@recaudoGuardar')->name('estudio.recaudo');
});

// ------------------------------------------------------------------
// CLIENTES
// ------------------------------------------------------------------
Route::group(['prefix' => 'clientes'], function () {
    Route::post('/crear', 'ClientesController@crear');
    Route::get('/editar/{id}', 'ClientesController@editar');
    Route::post('/actualizar', 'ClientesController@actualizar');
});

// ------------------------------------------------------------------
// DATASET
// ------------------------------------------------------------------
Route::get('/dataset', 'DatasetController@index');
Route::get('/dataset/get', 'DatasetController@get');

// ------------------------------------------------------------------
// PÁGINAS DE INFORMACIÓN (VISTAS SIMPLES)
// ------------------------------------------------------------------
Route::view('/welcome', 'welcome');
Route::view('/amipersonas', 'amipersonas');
Route::view('/ami', 'ami');
Route::view('/moreinformation', 'moreinformation');
Route::view('/moreinformationcartera', 'moreinformationcartera');
Route::view('/hego', 'hego');
Route::view('/hegoinformation', 'hegoinformation');
Route::view('/politicas', 'politicas');
Route::view('/contacto', 'contacto');
Route::view('/integration', 'integration');

// ------------------------------------------------------------------
// IMPORTS / DUMPS (DataMes, FechaVinc, etc.)
// ------------------------------------------------------------------
Route::post('datamesImport', 'DataMesController@import');
Route::post('fechaVincImport', 'FechaVincController@import');
Route::post('descapliImport', 'DescapliController@import');
Route::post('descnoapController', 'DescnoapController@import');
Route::post('datamesfiduImport', 'DatamesfiduController@import');
Route::post('datamessedvalleImport', 'DatamesSedValleController@import');

Route::get('dumpDataMes', 'DataMesController@dumpDataMes');
Route::get('dumpDataMesFidu', 'DatamesfiduController@dumpDatamesfidu');
Route::get('dumpDataMesSedValle', 'DatamesSedValleController@dumpDatamessedvalle');

// ------------------------------------------------------------------
// CONSULTAS UNITARIAS
// ------------------------------------------------------------------
Route::post('consultaDescnoap', 'DescnoapController@consultaUnitaria');
Route::post('consultaUnitaria', 'DescapliController@consultaUnitaria');
Route::post('resultadoAprobacion', 'DescapliController@resultadoAprobacion');

// Nuevas
Route::post('consultaDatamessemcali', 'DatamesSemCaliController@consultaUnitaria');
Route::post('consultaDeduccionessemcali', 'DeduccionesSemCaliController@consultaUnitaria');
Route::post('consultaSabana', 'SabanaController@consultaUnitaria');
Route::post('consultaDescuentossedvalle', 'DescuentossedvalleController@consultaUnitaria');
Route::post('consultaDescuentossedchoco', 'DescuentossedchocoController@consultaUnitaria');
Route::post('consultaDescuentossemsahagun', 'DescuentossemsahagunController@consultaUnitaria');
Route::post('consultaDescuentossemquibdo', 'DescuentosSemQuibdoController@consultaUnitaria');
Route::post('consultaDescuentossempopayan', 'DescuentossempopayanController@consultaUnitaria');
//Route::post('consultaDescuentossedcauca', 'DescuentossedcaucaController@consultaUnitaria');
Route::post('consultaDetalledecliente', 'DetalledeclienteController@consultaUnitaria');
Route::post('consultaDescuentossemcali', 'DescuentosSemCaliController@consultaUnitaria');
Route::post('consultaEmbargossemcali', 'EmbargosSemCaliController@consultaUnitaria');
Route::post('consultaEmbargossedvalle', 'EmbargosSedValleController@consultaUnitaria');
Route::post('consultaEmbargossedchoco', 'EmbargossedchocoController@consultaUnitaria');
Route::post('consultaEmbargossedcauca', 'EmbargossedcaucaController@consultaUnitaria');
Route::post('consultaEmbargossemquibdo', 'EmbargosSemQuibdoController@consultaUnitaria');

// ------------------------------------------------------------------
// RESOURCES
// ------------------------------------------------------------------
Route::resource('datamessemcali', 'DatamesSemCaliController');
Route::resource('deduccionessemcali', 'DeduccionesSemCaliController');
Route::resource('sabana', 'SabanaController');
Route::resource('Descuentossedvalle', 'DescuentosSedValleController');
Route::resource('Descuentossedchoco', 'DescuentossedchocoController');
Route::resource('Descuentossemquibdo', 'DescuentosSemQuibdoController');
Route::resource('Descuentossemsahagun', 'DescuentossemsahagunController');
Route::resource('Descuentossempopayan', 'DescuentossempopayanController');
Route::resource('Detalledecliente', 'DetalledeclienteController');
Route::resource('Descuentossemcali', 'DescuentosSemCaliController');
Route::resource('embargossemcali', 'EmbargosSemCaliController');
Route::resource('embargossedvalle', 'EmbargosSedValleController');
Route::resource('embargossedchoco', 'EmbargossedchocoController');
Route::resource('embargossedcauca', 'EmbargossedcaucaController');
Route::resource('embargossemquibdo', 'EmbargosSemQuibdoController');

// FIN
Route::resource('datamesfopep', 'DataMesController');
Route::resource('fechavinc', 'FechaVincController');
Route::resource('descapli', 'DescapliController');
Route::resource('descnoap', 'DescnoapController');

// ------------------------------------------------------------------
// VISADO
// ------------------------------------------------------------------
Route::post('detalleConsulta', 'VisadoController@detalleConsulta');
Route::post('pdfDetalle', 'VisadoController@pdfDetalle');
Route::get('getHistoryConsults', 'VisadoController@historialConsultas');


Route::prefix('/visados')->group(function () {
    Route::post('/', [VisadoController::class, 'store']);
    Route::post('/{id}', [VisadoController::class, 'update']);
});

Route::view('/historyClient', 'historyClient')->middleware('auth');
Route::view('/dataClient', 'dataClient');
Route::view('/dataClientDraft', 'dataClientDraft')->middleware(['auth', 'permission:hacer consultas']);
Route::view('/dataClientDraftwithoutvisa', 'dataClientDraftwithoutvisa');
Route::view('/refundCartera', 'refundCartera');
Route::view('/certificados', 'certificados');
Route::view('/massiveCharge', 'massive')
->name('credit-request.bulk');
// ------------------------------------------------------------------
// VALIDACIÓN
// ------------------------------------------------------------------
Route::resource('/validate', 'SolicitudValidacionController');

// ------------------------------------------------------------------
// DATAMESFIDU Y DATAMESEDVALLE
// ------------------------------------------------------------------
Route::resource('/datamesfidu', 'DatamesfiduController');
Route::post('/datamesfidu/consultaUnitaria', 'DatamesfiduController@consultaUnitaria');
Route::post('/datamessedvalle/consultaUnitaria', 'DatamesSedValleController@consultaUnitaria');
Route::resource('/datamessedvalle', 'DatamesSedValleController');

// ------------------------------------------------------------------
// COTIZER
// ------------------------------------------------------------------


Route::get('/cotizer-data/borrar/{id}', 'dataCotizerController@destroy');
Route::view('/solicitud', 'creditCalculator')->middleware('auth');
Route::view('/RegisterCredit', 'registerCredit')->name('register.credit');

// ------------------------------------------------------------------
// WHATSAPP-BOT
// ------------------------------------------------------------------
Route::apiResource('/whatsapp-bot', 'WhatsAppBotController');

// ------------------------------------------------------------------
// MIDDLEWARE AUTH
// ------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/get-coupons', 'CouponsController@index')->name('coupons.index');
    Route::post('/get-descuentos', [DescuentosController::class, 'index'])->name('descuentos');
    Route::post('/get-embargos', [EmbargosController::class, 'index'])->name('embargos');
});

// ------------------------------------------------------------------
// VENTAS DE CARTERA
// ------------------------------------------------------------------
Route::resource('ventaCartera', 'VentaCarteraController');

// ------------------------------------------------------------------
// CARTERA
// ------------------------------------------------------------------
Route::resource('cartera', 'CarteraController');

// ------------------------------------------------------------------
// REPORTE PLANTA COMERCIAL
// ------------------------------------------------------------------
Route::get('reporte-planta-comercial', 'ReportesController@reportePlantaComercial');

// ------------------------------------------------------------------
// CONTACTO
// ------------------------------------------------------------------
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ------------------------------------------------------------------
// CUPONES (Nuevos métodos Gabriel)
// ------------------------------------------------------------------
Route::post('/coupons/by-pagaduria', 'CouponsController@getFastCouponsByPagaduria');
Route::get('/coupons-form', 'CouponsController@showCouponsForm')->name('coupons.form');
Route::get('/pagadurias/names', 'PagaduriasController@getPagaduriasNames');
Route::get('/pagadurias/namesAmi', 'PagaduriasController@getPagaduriasNamesAmi');
Route::get('/situacion-laboral/{doc}', 'PagaduriasController@getSituacionLaboralByDoc');
Route::get('/incapacidad/{doc}/{month}/{year}', 'CouponsController@getIncapacidadByDoc')->name('incapacidad.byDoc');
Route::post('/api/situacion-laboral-batch', 'PagaduriasController@getSituacionLaboralByDocs');

Route::post('/descuentos/by-pagaduria', [DescuentosController::class, 'getDescuentosByPagaduria']);
Route::post('/embargos/by-pagaduria', [EmbargosController::class, 'getEmbargosByPagaduria']);

// ------------------------------------------------------------------
// ANÁLISIS DE CARTERA / DEMOGRÁFICO
// ------------------------------------------------------------------
Route::get('/analisis-de-cartera', [DemograficoController::class, 'show'])->name('demografico.show');
Route::view('/demografico', 'Demographic.IndexDemografico');
Route::post('/demografico/upload', [DemograficoController::class, 'upload'])->name('demografico.upload');
Route::get('/demografico/recent-consultations', [DemograficoController::class, 'getRecentConsultations']);
Route::get('/demografico/fetch-paginated-results', [DemograficoController::class, 'fetchPaginatedResults']);
Route::get('/demografico/fetch-paginated-results-demografico', [DemograficoController::class, 'fetchPaginatedResultsDemografico']);
Route::get('/demografico/calcular-cupo/{cedula}/{mes}/{ano}', [DemograficoController::class, 'calcularCupoPorCedula'])->name('demografico.calcularCupo');

// ------------------------------------------------------------------
// DOCUMENTOS (MIDDLEWARE AUTH)
// ------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/documents/{id}/update-status', [DocumentController::class, 'updateStatus'])->name('documents.update.status');
    Route::post('/documents/{id}/upload-pdf', [DocumentController::class, 'uploadPdf'])->name('documents.upload.pdf');
    Route::post('/documents/{id}/delete-pdf', [DocumentController::class, 'deletePdf'])->name('documents.delete.pdf');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{id}/download-pdf', [DocumentController::class, 'downloadPdf'])
         ->name('documents.download.pdf')
         ->middleware('auth');

    Route::get('/documents/template', [DocumentController::class, 'downloadTemplate']);
    Route::post('/documents/upload-bulk', [DocumentController::class, 'uploadBulk']);
});

// ------------------------------------------------------------------
// COLPENSIONES - JOINPENSIONES - FIDUPREVISORA
// ------------------------------------------------------------------
Route::get('/colpensiones', [UploadController::class, 'index']);
Route::post('/colpensiones/upload', [ColpensionesController::class, 'upload']);
Route::get('/colpensiones/progress/{progressKey}', [ColpensionesController::class, 'checkProgress']);

Route::get('/joinpensiones', [JoinPensionesController::class, 'index']);
Route::post('/joinpensiones/upload', [JoinPensionesController::class, 'upload']);
Route::post('/joinpensiones/search', [JoinPensionesController::class, 'search']);

Route::get('/fiduprevisora', [FiducidiariaController::class, 'index']);
Route::post('/fiduprevisora/upload', [FiducidiariaController::class, 'upload']);
Route::get('/fiduprevisora/progress/{progressKey}', [FiducidiariaController::class, 'checkProgress']);
Route::post('/fiduprevisora/search', [FiducidiariaController::class, 'search']);

Route::get('/file-upload-logs', [FileUploadLogController::class, 'getLogs']);

// ------------------------------------------------------------------
// PARÁMETROS COMPARATIVA FONDO/ORIGINADOR
// ------------------------------------------------------------------
Route::group(['prefix' => 'parametros-comparativa'], function () {
    Route::get('/', [ParametrosComparativaController::class, 'index'])->name('parametros-comparativa.index');
    Route::post('/store', [ParametrosComparativaController::class, 'store'])->name('parametros-comparativa.store');
    Route::get('/comparativa', [ParametrosComparativaController::class, 'comparativa'])->name('parametros_comparativa.comparativa');
    Route::post('/upload', [ParametrosComparativaController::class, 'upload'])->name('parametros_comparativa.upload');
    Route::get('/opciones', [ParametrosComparativaController::class, 'opciones'])->name('parametros-comparativa.opciones');
    // (en el código original aparecía 2 veces; solo se deja una)
});

// ------------------------------------------------------------------
// POLÍTICAS
// ------------------------------------------------------------------
Route::view('/politicas/autorizacion', 'politicas.autorizacion')->name('politicas.autorizacion');
Route::view('/politicas/tratamiento-datos', 'politicas.tratamiento')->name('politicas.tratamiento');

// ------------------------------------------------------------------
// JELÓU
// ------------------------------------------------------------------
Route::get('jelou/get-factor/{doc}', [JelouController::class, 'getFactorPerDoc']);
Route::get('jelou/candidates', [JelouController::class, 'getJelouCandidates']);
// ------------------------------------------------------------------
// RUTAS PARA LA VISTA DE CRÉDITO (CREDITREQUEST)
// ------------------------------------------------------------------
Route::view('/credit-request', 'CreditRequest.CreditForm')->name('credit-request.view');
Route::post('/credit-requests', [CreditRequestController::class, 'store'])->name('credit-request.store');
Route::get('/credit-requests/bulk', [CreditRequestController::class, 'bulkForm'])
     ->name('credit-request.bulk');


Route::post('/credit-requests/{id}/upload-visado-pdf',
    [CreditRequestController::class, 'uploadVisadoPdf'])
    ->middleware('auth'); 
Route::post('/credit-requests/bulk', [CreditRequestController::class, 'bulkStore']);
Route::get('/data-coverage/batch',        [DataCoverageController::class,'batch']);

//REVIEW CONTROL CARGA DATA
Route::middleware(['auth','permission:ver visado'])->group(function () {
    Route::view('/admin/data-coverage', 'Admin.DataCoverage')->name('data-coverage.view');


    // --- listado de pagadurías
    Route::get('/api/data-coverage',              [DataCoverageController::class,'index'])
         ->name('data-coverage.index');

    // --- cobertura por pagaduría  <---  NUEVA
    Route::get('/api/data-coverage/pagaduria',    [DataCoverageController::class,'pagaduria'])
         ->name('data-coverage.pagaduria');
});



// Listar/actualizar credit requests
Route::get('/credit-requests', [CreditRequestController::class, 'index'])->name('credit-request.index');
Route::get('/credit-requests/all', [CreditRequestController::class, 'getAll'])->name('credit-request.all');
Route::patch('/credit-requests/{id}/status', [CreditRequestController::class, 'updateStatus'])->name('credit-request.updateStatus');
Route::post('/credit-requests/{id}/documents', [CreditRequestController::class, 'uploadDocument']);
Route::get('/scredit-request/{id}/documents', [CreditRequestController::class, 'getDocuments']);
Route::patch('/credit-requests/{id}/visado', [CreditRequestController::class, 'markAsVisado'])->name('credit-request.markAsVisado');

// ------------------------------------------------------------------
// EMPRESAS (middleware de permiso)
// ------------------------------------------------------------------
Route::prefix('/empresas')->middleware('permission:ver empresas')->group(function () {
    Route::get('/', [EmpresaController::class, 'index']);
    Route::post('/', [EmpresaController::class, 'store']);
    Route::get('/crear', [EmpresaController::class, 'crear']);
    Route::get('/edit/{id}', [EmpresaController::class, 'edit']);
    Route::get('/ver/{id}', [EmpresaController::class, 'show']);
    Route::post('/{id}', [EmpresaController::class, 'update']);
    Route::delete('/{id}', [EmpresaController::class, 'destroy']);

    Route::get('/{id}/sedes', [EmpresaController::class, 'sedes']);
});

// ------------------------------------------------------------------
// SEDES (middleware de permiso)
// ------------------------------------------------------------------
Route::prefix('/sedes')->middleware('permission:ver sedes')->group(function () {
    Route::get('/', [SedeController::class, 'index']);
    Route::post('/', [SedeController::class, 'store']);
    Route::get('/crear', [SedeController::class, 'create']);
    Route::get('/editar/{sede}', [SedeController::class, 'edit']);
    Route::put('/{sede}', [SedeController::class, 'update']);
    Route::delete('/{sede}', [SedeController::class, 'destroy']);
});

// ------------------------------------------------------------------
// ÁREA COMERCIAL (middleware de permiso)
// ------------------------------------------------------------------
Route::prefix('/area-comerciales')->middleware('permission:ver area comercial')->group(function () {
    Route::get('/', [AreaComercialController::class, 'index']);
    Route::post('/', [AreaComercialController::class, 'store']);
    Route::get('/crear', [AreaComercialController::class, 'crear']);
    Route::get('/edit/{id}', [AreaComercialController::class, 'edit']);
    Route::get('/ver/{id}', [AreaComercialController::class, 'show']);
    Route::post('/{id}', [AreaComercialController::class, 'update']);
    Route::delete('/{id}', [AreaComercialController::class, 'destroy']);
});

// ------------------------------------------------------------------
// LISTAS
// ------------------------------------------------------------------
Route::prefix('/listas')->group(function () {
    Route::get('/tipo-empresas', [ListaController::class, 'listarTipoEmpresas']);
    Route::get('/tipo-sociedades', [ListaController::class, 'listarTipoSociedades']);
    Route::get('/tipo-documentos', [ListaController::class, 'listarTipoDocumentos']);
    Route::get('/ciudades', [ListaController::class, 'listarCiudades']);
    Route::get('/sedes', [ListaController::class, 'listarSedes']);
    Route::get('/ubicaciones', [ListaController::class, 'listarUbicaciones']);
    Route::get('/cargos', [ListaController::class, 'listarCargos']);
    Route::get('/amis', [ListaController::class, 'listarAmis']);
    Route::get('/hegos', [ListaController::class, 'listarHegos']);
    Route::get('/permisos', [ListaController::class, 'listarPermisos']);
    Route::get('/empresas', [ListaController::class, 'listarEmpresas']);
});


Route::view('/demografico', 'Demographic.IndexDemografico');


// Demográfico – vistas Vue
Route::view('/demografico/subir',      'Demographic.PendingDemographicUpload')
     ->name('demografico.pending.upload.page');

Route::view('/demografico/pendientes', 'Demographic.PendingDemographicUploadList')
     ->name('demografico.pending.list.page');

// Demográfico – API pendientes
Route::post('/demografico/pending-uploads',
    [DemograficoController::class,'uploadPending']
)->middleware(['auth','permission:demografico.pending.upload']);

// lista de pendientes
Route::get('/demografico/pending-uploads',
    [DemograficoController::class,'listPending']
);

Route::post('/demografico/pending-uploads/{id}/approve', [DemograficoController::class, 'approveUpload'])
     ->name('demografico.pending.approve');

     //muevo esta ruta dinamica al final

     Route::get('/demografico/{doc}', [DemograficoController::class, 'getDemograficoPorDoc'])->name('demografico.get');
