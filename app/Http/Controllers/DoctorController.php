<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;
use Throwable;

class DoctorController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public static function listadoctor(){

        $doctores = DB::table('doctores')
        ->get();
        return $doctores;
    }
    public static function guardar(Request $r){
        try{
            $cita = $r['cita'];
            $cita_fecha = DB::table('citas')->select('*')
            ->where('fecha', $cita['fecha'])
            ->where('equipo', $cita['equipo'])
            ->where('hora_inicio', $cita['hora_inicio'])
            ->update([ 'se_presento' => $cita['se_presento'], 'ci_doctor' => $cita['ci_doctor']]);
        }catch( Throwable $ex){
            return $ex;
        }
        
        Cache::forget('citas' . $cita['fecha']);
        return 'ok';
    }
}
