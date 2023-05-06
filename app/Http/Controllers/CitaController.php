<?php

namespace App\Http\Controllers;

use App\Models\citas;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //dd(request());

        $date = date_create(date(""), timezone_open('America/La_Paz'));
        $date = date_format($date, 'Y-m-d');
        /*if (Cache::has('citas' . $date)) {
            $cita_fecha =  Cache::get('citas' . $date);
        } else {*/
        /*$agenda = DB::table('agendas')
                ->join('persona_citas', 'agendas.ci_paciente', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();*/
        // Cache::put('citas' . $date, $cita_fecha);
        //}
        $config = DB::table('configuracions')->select('*')->get();
        return inertia('Comenzar', ['fecha_server' => $date, 'agenda' => []]);
        //  dd($date);
        //dd($users);
        //return Inertia::render('Comenzar', ['fechas' => $cita_fecha, 'valor' => $valor, 'config' => $config]);
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
    public static function guardar(Request $request)
    {
        //
        $persona_nueva = [
            'nombre' =>  $request['pn']['nombre'],
            'ap_paterno' =>  $request['pn']['ap_paterno'],
            'ap_materno' =>  $request['pn']['ap_materno'],
            'celular' =>  $request['pn']['celular'],
            'correo' =>  $request['pn']['correo'],
            'expendido' =>  $request['pn']['expendido'],
            'fecha_nacimiento' =>  $request['pn']['fecha_nacimiento'],
            'ci' =>  $request['pn']['ci'],
            'direccion' =>  $request['pn']['direccion'],
            'sexo' =>  $request['pn']['sexo'],
            'nom_municipio' => 'LA PAZ',
        ];
        try {
            DB::table('persona_citas')->insert([$persona_nueva]);
        } catch (\Throwable $th) {
            return $th;
        }
        return 'OK';
    }
    public static function update_persona(Request $request)
    {
        //
        $ci =  $request['pn']['ci'];
        $persona_nueva = [
            'nombre' =>  $request['pn']['nombre'],
            'ap_paterno' =>  $request['pn']['ap_paterno'],
            'ap_materno' =>  $request['pn']['ap_materno'],
            'celular' =>  $request['pn']['celular'],
            'correo' =>  $request['pn']['correo'],
            'expendido' =>  $request['pn']['expendido'],
            'fecha_nacimiento' =>  $request['pn']['fecha_nacimiento'],
            'direccion' =>  $request['pn']['direccion'],
            'sexo' =>  $request['pn']['sexo'],
            'nom_municipio' => 'LA PAZ',
        ];
        try {
            $resp = DB::table('persona_citas')
                ->where('ci', $ci)
                ->update($persona_nueva);
        } catch (\Throwable $th) {
            return $th;
        }

        return 'OK';
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function show($date)
    {
        //
        /*if (Cache::has('citas' . $date)) {
            $cita_fecha = Cache::get('citas' . $date);
        } else {
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }*/


        return $date;
    }
    public static function mostrar($date)
    {
        //
        if (Cache::has('citas' . $date)) {
            $cita_fecha = Cache::get('citas' . $date);
        } else {
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }
        return $cita_fecha;
    }

    public static function usuario($ci)
    {
        //
        //if (Cache::has('usuario' . $ci)) {
        //$cita_fecha = Cache::get('citas' . $ci);
        //}else {
        $opcion = 1;

        $datos = DB::table('persona_citas')->where('persona_citas.ci', $ci)->get();
        $citas = [];
        if (sizeof($datos) > 0) {
            $opcion = 2;
            $citas = DB::table('citas')
                ->where('citas.ci', $ci)
                ->get();
        }

        $data = [
            'datos' => $datos,
            'citas' => $citas,
            'opcion' => $opcion,
        ];

        //Cache::put('usuario' . $ci, $cita_fecha);
        //}
        return $data;
    }
    public static function cita_usuario($ci)
    {
        //
        //if (Cache::has('usuario' . $ci)) {
        //$cita_fecha = Cache::get('citas' . $ci);
        //}else {
        $cita_fecha = DB::table('citas')
            ->where('citas.ci', $ci)
            ->get();
        //Cache::put('usuario' . $ci, $cita_fecha);
        //}
        return $cita_fecha;
    }
    public static function citas_disponibles($fecha)
    {
        //
        //if (Cache::has('usuario' . $ci)) {
        //$cita_fecha = Cache::get('citas' . $ci);
        //}else {
        /*
        $cita_fecha = DB::table('citas')
            ->select(['hora_inicio', DB::raw('sum(case equipo WHEN 1 then 1 else 0 end) AS GRUPO1, 
                sum(case equipo WHEN 2 then 1 else 0 end) AS GRUPO2, 
                sum(case equipo WHEN 3 then 1 else 0 end) AS GRUPO3, 
                sum(case equipo WHEN 4 then 1 else 0 end) AS GRUPO4')])
            ->where('fecha', $fecha)
            ->groupBy('hora_inicio')
            ->get();

        //Cache::put('usuario' . $ci, $cita_fecha);
        //}
        $citas = [
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00"),
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00"),
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00"),
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00")
        ];
        $prueba = [];
        $valor = "";
        foreach ($cita_fecha as $value) {
            foreach ($value as $k => $v) {
                if ($k == 'hora_inicio') {
                    $valor = $v;
                    array_push($prueba, $valor);
                }
                if ($k == 'grupo1' && $v == 1) {

                    if (($key = array_search($valor, $citas[0])) !== false) {
                        unset($citas[0][$key]);
                    }
                }
                if ($k == 'grupo2' && $v == 1) {
                    if (($key = array_search($valor, $citas[1])) !== false) {
                        unset($citas[1][$key]);
                    }
                }
                if ($k == 'grupo3' && $v == 1) {
                    if (($key = array_search($valor, $citas[2])) !== false) {
                        unset($citas[2][$key]);
                    }
                }
                if ($k == 'grupo4' && $v == 1) {
                    if (($key = array_search($valor, $citas[3])) !== false) {
                        unset($citas[3][$key]);
                    }
                }
            }
        }*/
        return $fecha;
    }
    public static function dar_cita(Request $resquest)
    {
        $cita = request('cita');
        $date = $cita['fecha'];
        //return $cita;
        unset($cita['hora_inicio']);
        try {
            $resp = DB::table('citas')
                ->insert($cita);
        } catch (\Throwable $th) {
            return  Response::json(['hello' => $th], 500);
        }
        if (Cache::has('citas' . $date)) {
            Cache::forget('citas' . $date);
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }
        $las_citas = DB::table('citas')
            ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
            ->get();
        return $las_citas;
    }
    public static function update_cita(Request $resquest)
    {
        $cita_antigua = request('cita_anterior');
        $cita = request('cita_nueva');
        $date = $cita['fecha'];
        unset($cita['nombres']);
        unset($cita['ap_materno']);
        unset($cita['ap_paterno']);
        unset($cita['celular']);
        unset($cita['expedido']);
        unset($cita['fecha_nacimiento']);
        unset($cita['sexo']);
        unset($cita['nom_departamento']);
        unset($cita['nom_municipio']);
        unset($cita['correo']);
        unset($cita['direccion']);
        unset($cita['register']);

        //return $cita;
        try {
            $resp = DB::table('citas')
                ->where('fecha', '=', $cita_antigua['fecha'])
                ->where('hora_inicio', '=', $cita_antigua['hora_inicio'])
                ->where('equipo', '=', $cita_antigua['equipo'])
                ->update($cita);
        } catch (\Throwable $th) {
            return $th;
        }
        if (Cache::has('citas' . $date)) {
            Cache::forget('citas' . $date);
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }

        $cita_fecha = DB::table('citas')
            ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
            ->where('fecha', $date)
            ->get();

        return $cita_fecha;
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
    public static function eliminar_cita(Request $request)
    {


        $cita = $request['cita'];

        return $cita;
    }
    public static function eliminar_cita2(Request $request)
    {

        /*$cita = $request['cita'];
        $cita_fecha = DB::table('citas')->select('*')
        ->where('fecha', $cita['fecha'])
        ->where('equipo', $cita['equipo'])
        ->where('hora_inicio', $cita['hora_inicio'])
        ->delete();
        Cache::forget('citas' . $cita['fecha']);
        */
        return $request['cita'];
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
    public static function feriado(string $s)
    {    //
        
        //$date = new DateTime(''.$s);
        //$format= date_format($date, 'Y-m-d');
        DB::statement("SET datestyle = 'ISO, DMY'");
        
        $year =  $s;
        
        try {
            //code...
            $query = DB::table("calendarios")
                ->select(DB::raw("TRIM(to_char (fecha, 'day'))"), 
                DB::raw("case when to_char (fecha, 'day') = 'saturday' then date (fecha::date + interval '2 days') when trim (to_char(fecha, 'day')) = 'sunday' then date (fecha::date + interval '1 days') else fecha end"))
                ->where("atencion", "=", 'feriado')
                ->where(DB::raw("TRIM(to_char(fecha, 'yyyy'))"), '=', db::raw("TRIM('".$year."')"))
                ->get();
            if (count($query) == 0) {
                
                $feriados = [
                    [
                        'fecha' => '01-01-' . $year,
                        'codigo' => '01',
                        'atencion' => 'feriado',
                        'descripcion' => 'Año nuevo',
                    ],
                    [
                        'fecha' => '22-01-' . $year,
                        'codigo' => '01',
                        'atencion' => 'feriado',
                        'descripcion' => 'Estado Plurinacional de Bolivia',
                    ],
                    [
                        'fecha' => '01-05-' . $year,
                        'codigo' => '01',
                        'atencion' => 'feriado',
                        'descripcion' => 'Día del Trabajador'
                    ],
                    [
                        'fecha' => '06-08-' . $year,
                        'codigo' => '01',
                        'atencion' => 'feriado',
                        'descripcion' => 'Día de la Independencia de Bolivia'
                    ],
                    [
                        'fecha' => '02-11-' . $year,
                        'codigo' => '01',
                        'atencion' => 'feriado',
                        'descripcion' => 'Día de los Difuntos'
                    ],
                    [
                        'fecha' => '25-12-' . $year,
                        'codigo' => '01',
                        'atencion' => 'feriado',
                        'descripcion' => 'Navidad'
                    ],

                ];
                foreach ($feriados as $key => $value) {
                    # code...
                    try {
                        //code...
                        db::table('calendarios')->insert($value);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                
            }
            $query = DB::table("calendarios")
                    ->select('*',DB::raw("case when to_char (fecha, 'day') = 'saturday' then date (fecha::date + interval '2 days') when trim (to_char(fecha, 'day')) = 'sunday' then date (fecha::date + interval '1 days') else fecha end as fecha"))
                    ->where("atencion", "=", 'feriado')
                    ->where(DB::raw("TRIM(to_char(fecha, 'yyyy'))"),'=', db::raw("TRIM('".$year."')"))
                    ->get();
            return $query;
        } catch (\Throwable $th) {
            throw $th;
        }
        return $s;
    }
}
