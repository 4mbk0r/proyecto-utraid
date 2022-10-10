<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Exception;
use Exception as GlobalException;
use Illuminate\Database\QueryException;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list_config = DB::table('configuracions')
            ->select('*')
            ->where('activo', '=', true)
            ->get();
        date_default_timezone_set("America/La_Paz");
        $date = date_create();
        $date = date_format($date, "Y-m-d H:i:s");
        return inertia('Configuracions', [
            'configuracion' => $list_config,
            'fecha_server' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $edit = $request['datos'];
        $n_fecha_final = date($edit['fecha_inicio']);
        $n_fecha_final = date("Y-m-d", strtotime($n_fecha_final . "- 1 days"));

        try {
            $default_actual =  DB::table('configuracions')
                ->where('principal', true)
                ->update(['principal' => false, 'fecha_final' => $n_fecha_final]);

            $edit['historial'] = $edit['id'];
            unset($edit['id']);
            $default_actual =  DB::table('configuracions')
                ->insert($edit);
            $list_config = DB::table('configuracions')
                ->select('*')
                ->where('activo', '=', true)
                ->get();
        } catch (\Throwable $th) {
            return $th;
        }
        return $list_config;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(int $configuracion)
    {
        //
        $dato = DB::table('salas')->where('id', $configuracion)->get();
        $n_salas =  count($dato);
        $datos = [
            'salas' => $dato,
            'n_sala' => $n_salas
        ];
        return $datos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        //

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $configuracion)
    {
        //
        try {
            $list_item = DB::table('configuracions')->where('id', '=', $configuracion)->get();
            if (count($list_item) == 1) {
                $item = $list_item[0];
                DB::table('configuracions')->where('historial', '=', $item->id)
                    ->update(['historial' => $item->historial]);
                DB::table('configuracions')->where('id', '=', $item->historial)
                    ->update(['fecha_final' => $item->fecha_final, 'principal' => $item->principal]);
                DB::table('configuracions')->delete($item->id);
            }
        } catch (\Throwable $th) {
            return $th;
        }
        $list_config = DB::table('configuracions')
            ->select('*')
            ->where('activo', '=', true)
            ->get();
        return $list_config;
    }
}
/**
PDOException: SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  la sintaxis de entrada no es válida para integer: «id» in C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php:526
Stack trace:
#0 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(526): PDOStatement->execute()
#1 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(705): Illuminate\Database\Connection->Illuminate\Database\{closure}('delete from "co...', Array)
#2 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(672): Illuminate\Database\Connection->runQueryCallback('delete from "co...', Array, Object(Closure))
#3 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(533): Illuminate\Database\Connection->run('delete from "co...', Array, Object(Closure))
#4 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(478): Illuminate\Database\Connection->affectingStatement('delete from "co...', Array)
#5 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php(3218): Illuminate\Database\Connection->delete('delete from "co...', Array)
#6 C:\xampp\htdocs\main\app\Http\Controllers\ConfiguracionController.php(134): Illuminate\Database\Query\Builder->delete('id', 3)
#7 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Controller.php(54): App\Http\Controllers\ConfiguracionController->destroy(3)
#8 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php(45): Illuminate\Routing\Controller->callAction('destroy', Array)
#9 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Route.php(262): Illuminate\Routing\ControllerDispatcher->dispatch(Object(Illuminate\Routing\Route), Object(App\Http\Controllers\ConfiguracionController), 'destroy')
#10 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Route.php(205): Illuminate\Routing\Route->runController()
#11 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(721): Illuminate\Routing\Route->run()
#12 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(128): Illuminate\Routing\Router->Illuminate\Routing\{closure}(Object(Illuminate\Http\Request))
#13 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Auth\Middleware\EnsureEmailIsVerified.php(30): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#14 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Auth\Middleware\EnsureEmailIsVerified->handle(Object(Illuminate\Http\Request), Object(Closure))
#15 C:\xampp\htdocs\main\vendor\inertiajs\inertia-laravel\src\Middleware.php(82): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#16 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Inertia\Middleware->handle(Object(Illuminate\Http\Request), Object(Closure))
#17 C:\xampp\htdocs\main\vendor\laravel\jetstream\src\Http\Middleware\ShareInertiaData.php(61): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#18 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Laravel\Jetstream\Http\Middleware\ShareInertiaData->handle(Object(Illuminate\Http\Request), Object(Closure))
#19 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php(50): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#20 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Routing\Middleware\SubstituteBindings->handle(Object(Illuminate\Http\Request), Object(Closure))
#21 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php(44): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#22 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Auth\Middleware\Authenticate->handle(Object(Illuminate\Http\Request), Object(Closure), 'sanctum')
#23 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php(78): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#24 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\VerifyCsrfToken->handle(Object(Illuminate\Http\Request), Object(Closure))
#25 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php(49): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#26 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\View\Middleware\ShareErrorsFromSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#27 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Session\Middleware\AuthenticateSession.php(58): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#28 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Session\Middleware\AuthenticateSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#29 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(121): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#30 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(64): Illuminate\Session\Middleware\StartSession->handleStatefulRequest(Object(Illuminate\Http\Request), Object(Illuminate\Session\Store), Object(Closure))
#31 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Session\Middleware\StartSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#32 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php(37): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#33 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse->handle(Object(Illuminate\Http\Request), Object(Closure))
#34 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php(67): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#35 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Cookie\Middleware\EncryptCookies->handle(Object(Illuminate\Http\Request), Object(Closure))
#36 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(103): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#37 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(723): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#38 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(698): Illuminate\Routing\Router->runRouteWithinStack(Object(Illuminate\Routing\Route), Object(Illuminate\Http\Request))
#39 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(662): Illuminate\Routing\Router->runRoute(Object(Illuminate\Http\Request), Object(Illuminate\Routing\Route))
#40 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(651): Illuminate\Routing\Router->dispatchToRoute(Object(Illuminate\Http\Request))
#41 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(167): Illuminate\Routing\Router->dispatch(Object(Illuminate\Http\Request))
#42 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(128): Illuminate\Foundation\Http\Kernel->Illuminate\Foundation\Http\{closure}(Object(Illuminate\Http\Request))
#43 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(21): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#44 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php(31): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#45 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull->handle(Object(Illuminate\Http\Request), Object(Closure))
#46 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(21): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#47 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php(40): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#48 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\TrimStrings->handle(Object(Illuminate\Http\Request), Object(Closure))
#49 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ValidatePostSize.php(27): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#50 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\ValidatePostSize->handle(Object(Illuminate\Http\Request), Object(Closure))
#51 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php(86): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#52 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance->handle(Object(Illuminate\Http\Request), Object(Closure))
#53 C:\xampp\htdocs\main\vendor\fruitcake\laravel-cors\src\HandleCors.php(38): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#54 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Fruitcake\Cors\HandleCors->handle(Object(Illuminate\Http\Request), Object(Closure))
#55 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php(39): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#56 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Http\Middleware\TrustProxies->handle(Object(Illuminate\Http\Request), Object(Closure))
#57 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(103): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#58 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(142): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#59 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(111): Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter(Object(Illuminate\Http\Request))
#60 C:\xampp\htdocs\main\public\index.php(52): Illuminate\Foundation\Http\Kernel->handle(Object(Illuminate\Http\Request))
#61 C:\xampp\htdocs\main\server.php(21): require_once('C:\\xampp\\htdocs...')
#62 {main}

Next Illuminate\Database\QueryException: SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  la sintaxis de entrada no es válida para integer: «id» (SQL: delete from "configuracions" where "configuracions"."id" = id) in C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php:712
Stack trace:
#0 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(672): Illuminate\Database\Connection->runQueryCallback('delete from "co...', Array, Object(Closure))
#1 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(533): Illuminate\Database\Connection->run('delete from "co...', Array, Object(Closure))
#2 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Connection.php(478): Illuminate\Database\Connection->affectingStatement('delete from "co...', Array)
#3 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php(3218): Illuminate\Database\Connection->delete('delete from "co...', Array)
#4 C:\xampp\htdocs\main\app\Http\Controllers\ConfiguracionController.php(134): Illuminate\Database\Query\Builder->delete('id', 3)
#5 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Controller.php(54): App\Http\Controllers\ConfiguracionController->destroy(3)
#6 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php(45): Illuminate\Routing\Controller->callAction('destroy', Array)
#7 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Route.php(262): Illuminate\Routing\ControllerDispatcher->dispatch(Object(Illuminate\Routing\Route), Object(App\Http\Controllers\ConfiguracionController), 'destroy')
#8 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Route.php(205): Illuminate\Routing\Route->runController()
#9 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(721): Illuminate\Routing\Route->run()
#10 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(128): Illuminate\Routing\Router->Illuminate\Routing\{closure}(Object(Illuminate\Http\Request))
#11 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Auth\Middleware\EnsureEmailIsVerified.php(30): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#12 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Auth\Middleware\EnsureEmailIsVerified->handle(Object(Illuminate\Http\Request), Object(Closure))
#13 C:\xampp\htdocs\main\vendor\inertiajs\inertia-laravel\src\Middleware.php(82): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#14 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Inertia\Middleware->handle(Object(Illuminate\Http\Request), Object(Closure))
#15 C:\xampp\htdocs\main\vendor\laravel\jetstream\src\Http\Middleware\ShareInertiaData.php(61): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#16 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Laravel\Jetstream\Http\Middleware\ShareInertiaData->handle(Object(Illuminate\Http\Request), Object(Closure))
#17 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php(50): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#18 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Routing\Middleware\SubstituteBindings->handle(Object(Illuminate\Http\Request), Object(Closure))
#19 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php(44): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#20 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Auth\Middleware\Authenticate->handle(Object(Illuminate\Http\Request), Object(Closure), 'sanctum')
#21 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php(78): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#22 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\VerifyCsrfToken->handle(Object(Illuminate\Http\Request), Object(Closure))
#23 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php(49): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#24 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\View\Middleware\ShareErrorsFromSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#25 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Session\Middleware\AuthenticateSession.php(58): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#26 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Session\Middleware\AuthenticateSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#27 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(121): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#28 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(64): Illuminate\Session\Middleware\StartSession->handleStatefulRequest(Object(Illuminate\Http\Request), Object(Illuminate\Session\Store), Object(Closure))
#29 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Session\Middleware\StartSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#30 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php(37): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#31 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse->handle(Object(Illuminate\Http\Request), Object(Closure))
#32 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php(67): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#33 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Cookie\Middleware\EncryptCookies->handle(Object(Illuminate\Http\Request), Object(Closure))
#34 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(103): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#35 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(723): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#36 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(698): Illuminate\Routing\Router->runRouteWithinStack(Object(Illuminate\Routing\Route), Object(Illuminate\Http\Request))
#37 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(662): Illuminate\Routing\Router->runRoute(Object(Illuminate\Http\Request), Object(Illuminate\Routing\Route))
#38 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Routing\Router.php(651): Illuminate\Routing\Router->dispatchToRoute(Object(Illuminate\Http\Request))
#39 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(167): Illuminate\Routing\Router->dispatch(Object(Illuminate\Http\Request))
#40 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(128): Illuminate\Foundation\Http\Kernel->Illuminate\Foundation\Http\{closure}(Object(Illuminate\Http\Request))
#41 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(21): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#42 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php(31): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#43 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull->handle(Object(Illuminate\Http\Request), Object(Closure))
#44 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(21): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#45 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php(40): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#46 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\TrimStrings->handle(Object(Illuminate\Http\Request), Object(Closure))
#47 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ValidatePostSize.php(27): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#48 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\ValidatePostSize->handle(Object(Illuminate\Http\Request), Object(Closure))
#49 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php(86): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#50 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance->handle(Object(Illuminate\Http\Request), Object(Closure))
#51 C:\xampp\htdocs\main\vendor\fruitcake\laravel-cors\src\HandleCors.php(38): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#52 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Fruitcake\Cors\HandleCors->handle(Object(Illuminate\Http\Request), Object(Closure))
#53 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php(39): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#54 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(167): Illuminate\Http\Middleware\TrustProxies->handle(Object(Illuminate\Http\Request), Object(Closure))
#55 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(103): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#56 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(142): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#57 C:\xampp\htdocs\main\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(111): Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter(Object(Illuminate\Http\Request))
#58 C:\xampp\htdocs\main\public\index.php(52): Illuminate\Foundation\Http\Kernel->handle(Object(Illuminate\Http\Request))
#59 C:\xampp\htdocs\main\server.php(21): require_once('C:\\xampp\\htdocs...')
#60 {main}
 * 
 */
