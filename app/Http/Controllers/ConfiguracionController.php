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
        $users = DB::select('select * from configuracions');
        return $users;
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Configuracion $configuracion)
    {
        //
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
        try {
            $datos = [
                'lugar' => $request['lugar'],
                'feriados' =>  json_encode($request['feriados']),
                'dias' => json_encode($request['dias'])
            ];
        } catch (GlobalException $ex) {
            return $ex;
        };
        $dato = DB::table('configuracions')->where('lugar', $request['lugar'])->get();

        if (sizeof($dato) <= 0) {
            try {

                DB::table('configuracions')->insert($datos);
                // Closures include ->first(), ->get(), ->pluck(), etc.
            } catch (QueryException $ex) {
                return ($ex->getMessage());
                // Note any method of class PDOException can be called on $ex.
            }
            return 'nuevo';
        } else {
            try {
                DB::table('configuracions')->where('lugar', $request['lugar'])->update($datos);
                // Closures include ->first(), ->get(), ->pluck(), etc.
            } catch (QueryException $ex) {
                return ($ex);
                // Note any method of class PDOException can be called on $ex.
            }
            return  $request['data'];
        }


        return $dato;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }
}
