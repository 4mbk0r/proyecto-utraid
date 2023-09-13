<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return DB::table('cargos')
            ->select('*')
            //->where('cargo', '!=', 'Admin')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        //
        try {
            $resp = (array)$request['formulario'];
            //return $resp;
            if (DB::table('cargos')->where('cargo', $resp['cargo'])->count() === 0) {
                DB::table('cargos')->insert($resp);
                // Puedes realizar la inserción aquí, ya que el valor es único.
            } else {
                // El valor ya existe en la tabla, maneja este caso según tus necesidades.
                DB::table('cargos')
                    ->where('cargo', $resp['cargo'])
                    ->update($resp);
            }
            // Procesamiento exitoso, mostrar mensaje de éxito
            return response()->json(['message' => '¡Datos insertados correctamente!']);
        } catch (QueryException $e) {
            // Error de inserción en la base de datos
            return response()->json(['error' => 'Error al insertar los datos: ' . $e->getMessage()], 500);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' =>  $th], 500);
        }
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show(String $cargo)
    {
        //
        $resultados = Cargo::leftJoin('users', 'cargos.cargo', '=', 'users.cargo')
            ->where('users.username', Auth::user()->username)
            ->select('cargos.*')
            ->first();
        return $resultados;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Cargo $cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cargo $cargo)
    {
        //
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy($cargo)
    {
        $objeto = json_decode($cargo);
        $cargo = $objeto->cargo;
        try {
            $sw = DB::table('cargos')->where('cargo', '=', $cargo)->delete();
            if ($sw) {
                return response()->json(['message' => '¡Datos eliminados con existo!']);
            } else {
                return response()->json(['message' => '¡Datos no se puedo eliminar. Por que no existe!']);
            }
        } catch (QueryException $e) {
            // Error de inserción en la base de datos
            return response()->json(['error' => 'Error al insertar los datos: ' . $e->getMessage()], 500);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' =>  $th], 500);
        }
    }
    public static function cargo_servicio()
    {
        //
        return DB::table('cargos')
            ->select('*')
            ->where('cargo', '!=', 'Admin')
            ->where('servicio', '=', 'true')
            ->get();
    }
}
