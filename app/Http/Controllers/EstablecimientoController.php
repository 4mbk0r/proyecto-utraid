<?php

namespace App\Http\Controllers;

use App\Models\establecimiento;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstablecimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return DB::table('establecimientos')
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
        //
        
        try {
            $resp = (array)$request['formulario'];
            DB::table('establecimientos')->insert($resp);
            // Procesamiento exitoso, mostrar mensaje de éxito
            return response()->json(['message' => '¡Datos insertados correctamente!']);
        }
        catch (QueryException $e) {
            // Error de inserción en la base de datos
            return response()->json(['error' => 'Error al insertar los datos: ' . $e->getMessage()], 500);
        }  
        catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' =>  $th], 500);
        }
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function show(establecimiento $establecimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(establecimiento $establecimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $establecimiento)
    {
        //
        $nuevo = $request['formulario'];
        $anterior = $request['anterior'];
        //return $nuevo['nombre'];
        try {
            $sw = DB::table('establecimientos')->where('nombre', '=', $anterior['nombre'])->update($nuevo);
            if($sw){
                return response()->json(['message' => '¡Datos actulizados  con existo!']);
            }else{
                return response()->json(['message' => '¡Datos no se puedo actulizado. Por que no existe!']);
            }
        }catch (QueryException $e) {
            // Error de inserción en la base de datos
            return response()->json(['error' => 'Error al insertar los datos: ' . $e->getMessage()], 500);
        }  
        catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' =>  $th], 500);
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy($establecimiento)
    {
        //
        $objeto = json_decode($establecimiento);
        $nombre = $objeto->nombre;
        try {
            $sw = DB::table('establecimientos')->where('nombre', '=', $nombre)->delete();
            if($sw){
                return response()->json(['message' => '¡Datos eliminados con existo!']);
            }else{
                return response()->json(['message' => '¡Datos no se puedo eliminar. Por que no existe!']);
            }
        }catch (QueryException $e) {
            // Error de inserción en la base de datos
            return response()->json(['error' => 'Error al insertar los datos: ' . $e->getMessage()], 500);
        }  
        catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' =>  $th], 500);
        }
    }
}
