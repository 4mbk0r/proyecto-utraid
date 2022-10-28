<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\PdfViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaAntiguoController;
use App\Http\Controllers\PersonaCitaController;
use App\Models\personas_antiguo;
use App\Http\Controllers\CitaTieneConfiguracionController;
use App\Models\Configuracion;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('articles', function ($id) {
    return PersonaAntiguoController::mostrar($id);
});

Route::get('/citas_fecha/{id}', function ($id) {
    return CitaController::mostrar($id);
});

Route::post('/datos_usuario/{id}', function ($id) {
    return CitaController::usuario($id);
});

Route::post('/datos_citas/{id}', function ($id) {
    return CitaController::cita_usuario($id);
});

Route::get('/citas_actuales/{fecha}', function ($fecha) {
    return CitaController::citas_disponibles($fecha);
});

Route::post('/guardar_citas', function (Request $request) {
    return CitaController::guardar($request);
});


Route::post('/update_usuario', function (Request $request) {
    return CitaController::update_persona($request);
});



Route::post('/dar_cita', function (Request $request) {
    return CitaController::dar_cita($request);
});
Route::post('/update_cita', function (Request $request) {
    return CitaController::update_cita($request);
});

Route::post('/imprimir', function (Request $request) {
    return PdfViewController::store($request);
});


Route::get('/doctor', function (Request $request) {
    return DoctorController::listadoctor();
});

Route::post('/eliminar_cita', function (Request $request) {
    return CitaController::eliminar_cita($request);
});

Route::post('/eliminar_cita2', function (Request $request) {
    return CitaController::eliminar_cita($request);
});

Route::post('/guardar_datos', function (Request $request) {
    return DoctorController::guardar($request);
});


Route::post('/buscar_persona', function (Request $request) {
    return PersonaCitaController::buscar_persona($request);
});

Route::get('/buscar_persona/{ci}', function (String $ci) {
    return PersonaCitaController::buscar_persona_ci($ci);
});

Route::post('/guardar_persona', function (Request $request) {
    return PersonaCitaController::guardar_persona($request);
});

Route::get('/verificar_fecha/{fecha}', function (String $fecha) {
    return CitaTieneConfiguracionController::verificar_fecha($fecha);
});

Route::post('/verificar_fecha', function (Request $request) {
    return CitaTieneConfiguracionController::verificar_rangofecha($request);
});



Route::post('/delete_sala', function (Request $request) {
    return SalaController::eliminar($request);
});


Route::post('/buscar_consultorios', function (Request $request) {

    return AgendaController::buscar_consultorios($request);
});

Route::get('/fechas_validas', function (Request $request) {

    return ConfiguracionController::fechas_invalidos();
});
