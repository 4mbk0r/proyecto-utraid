<?php

namespace App\Http\Controllers;

use App\Models\persona;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Symfony\Component\VarDumper\Cloner\Data;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return DB::table('personas')
        ->select('*')->get();

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
     * @param  \App\Models\persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function show(persona $persona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function edit(persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, persona $persona)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy(persona $persona)
    {
        //
    }
    public static function buscar_valor(Request $request)
    {
        
        $persona =(array) $request['paciente'];
        
        $r =[];
        /*Buscar nombre, apellido en una tabla */
        foreach ($persona as $key => $value) {
            # code...
            //array_push($r, $key);
            if(!empty($value)){
                $p = 'unaccent(lower('.strval($key).')) like '.$value; 
                /*if(!empty($r)){
                    $r .= $r.' and '.$p;
                }else{
                    $r = $p;
                }*/
                $p =  [DB::raw("unaccent(lower(".strval($key)."))"), 'like', strtolower($value).'%'];
                array_push($r, $p);
            }
        }
        //return $r;  
        $t = [];
        if(count($r)>0){
            $t =db::table('personas')
            ->leftJoin('evaluacions', 'evaluacions.id_persona', '=', 'personas.id')
            ->where($r)
            ->get();
        }
        return $t;


    }
    public function indexView()
    {
        //
        return DB::table('personas')
        ->select('*')->get();

    }
}
