<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\ConfSalaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\CitaTieneConfiguracionController;
use App\Http\Controllers\AgendaController;

use App\Http\Controllers\CalendariolinealController;

use App\Http\Controllers\Administracion\Registro;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\UserController;

//personal

use App\Http\Controllers\PersonalController;

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\Controllgeneral;

use App\Http\Controllers\CuentaController;
use App\Http\Controllers\EquipoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PersonaAntiguoController;
use App\Http\Controllers\PersonaCitaController;
use App\Http\Controllers\PermisoController;
use App\Models\agenda;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


/*Administracion */
Route::get('/registrar', [Registro::class, 'index'])->name('registro');



/*SALAS*/

Route::resource('/conf_sala', ConfSalaController::class);
Route::resource('/cargo', CargoController::class);


Route::resource('/personal', PersonalController::class);
/**
 * 
 * 
 * Registrar
 * Registrar lista
 */
Route::resource('/lista_personal', PersonalController::class);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return 'ddd';
})->name('Dashboard');

Route::middleware(['auth:sanctum', 'verified', 'permiso'])->get('/inicio', [CuentaController::class, 'index'],)->name('inicio');


Route::resource('/agendar', AgendaController::class)->middleware(['auth:sanctum', 'verified']);
Route::middleware(['auth:sanctum', 'verified', 'permiso'])->get('/agenda', [AgendaController::class, 'index'],)->name('agendar');



Route::resource('/persona_antigua', PersonaAntiguoController::class)->middleware(['auth:sanctum', 'verified']);

Route::resource('/ususario', UserController::class)->middleware(['auth:sanctum', 'verified']);



Route::middleware(['auth:sanctum', 'verified'])->get('/administrar', function () {
    return Inertia::render('Administrador');
})->name('administrar');
Route::resource('/administrar', PersonaCitaController::class)->middleware(['auth:sanctum', 'verified']);

Route::middleware(['auth:sanctum', 'verified'])->get('/pdf', function () {
    return view('myPDF');
})->name('pdf');

//Route::middleware()
Route::get('/admin', function () {
    return Inertia::render('Auth/LoginAdmin');;
})->name('admin');

Route::post('/loginadmin', function () {
})->name('loginadmin');



Route::resource('/configs', ConfiguracionController::class)->middleware(['auth:sanctum', 'verified']);



Route::get('/imprimir', function () {
    return Inertia::render('Micomponet/imprimir');;
})->name('imprimir');


Route::resource('/configurar', ConfigController::class);


Route::get('/configura', function () {
    return Inertia::render('Micomponet/Configura');;
})->name('configura');

Route::resource('/configurageneral', Controllgeneral::class);


/**Permisos */
Route::resource('/permiso', PermisoController::class);

/*Configuraciones */
Route::resource('/configuracion', ConfiguracionController::class)->middleware(['auth:sanctum', 'verified']);;


Route::middleware(['auth:sanctum', 'verified'])->get('/configuracion', function () {
    return Inertia::render('Configuracions');
})->name('configuracion');



Route::resource('/sala', SalaController::class)->middleware(['auth:sanctum', 'verified']);;
Route::resource('/horario', HorarioController::class);

Route::resource('/lista_configuracion', CitaTieneConfiguracionController::class)->middleware(['auth:sanctum', 'verified']);;


Route::resource('/lista_agenda', AgendaController::class)->middleware(['auth:sanctum', 'verified']);;


Route::get('/configuracion3', function () {
    return Inertia::render('Configuracion2');
})->name('agenda');

Route::resource('/calendariolineal', CalendariolinealController::class); //->middleware(['auth:sanctum', 'verified']);;



Route::get('/equipos', function () {
    return Inertia::render('Configuracion/Equipo');
})->name('equipos');

Route::get('/salaespera', function () {
    return Inertia::render('Micomponet/SalaEspera');
})->name('salaespera');



Route::get('/excel', function () {
    return Inertia::render('Micomponet/Excel');
})->name('excelss');



Route::resource('/equipos2', EquipoController::class);
