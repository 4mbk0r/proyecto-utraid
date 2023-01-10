<?php

namespace App\Http\Controllers;

use App\Models\Asignar_config_sala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignarConfigSalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asignar_config_sala  $asignar_config_sala
     * @return \Illuminate\Http\Response
     */
    public function show(Asignar_config_sala $asignar_config_sala)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asignar_config_sala  $asignar_config_sala
     * @return \Illuminate\Http\Response
     */
    public function edit(Asignar_config_sala $asignar_config_sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asignar_config_sala  $asignar_config_sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asignar_config_sala $asignar_config_sala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asignar_config_sala  $asignar_config_sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asignar_config_sala $asignar_config_sala)
    {
        //
    }
    
    public static function listar_salas(int $id_configuracion){
        $query = DB::table('asignar_config_salas')
        ->leftJoin('salas', 'asignar_config_salas.id_sala', '=', 'salas.id')
        ->leftJoin('conf_salas', 'asignar_config_salas.id_conf', '=', 'conf_salas.id')
        ->where('asignar_config_salas.id_conf', '=', $id_configuracion)
        ->get();
        return $query;
    }
}
