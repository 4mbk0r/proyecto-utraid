<?php

namespace App\Http\Controllers;

use App\Models\agenda;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AgendaController extends Controller
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
        $cita = $request['cita'];
        unset($cita['hora_inicio']);
        try {
            $query = DB::table('cita_tiene_configuracions')->where('fecha', '=', $cita['fecha'])->get();
            if (sizeof($query) == 0) {
                $query = DB::table('configuracions')->where('fecha_inicio', '<=', $cita['fecha'])
                    ->where('fecha_final', '>=', $cita['fecha'])
                    ->where('tipo', '=', 'permanente')
                    ->get();
                $use = [
                    'fecha' => $cita['fecha'],
                    'id' => $query[0]->id
                ];
                $query = DB::table('cita_tiene_configuracions')->insert($use);
            }
            DB::table('agendas')->insert($cita);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'You need to add a card first'], 409);
        }

        return $request['cita'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(string $agenda)
    {
        //

        $date = date_create(date($agenda), timezone_open('America/La_Paz'));
        $date = date_format($date, 'Y-m-d');
        /*if (Cache::has('citas' . $date)) {
            $cita_fecha =  Cache::get('citas' . $date);
        } else {*/


        $agenda = DB::table('agendas')
            ->leftJoin('persona_citas', 'agendas.ci_paciente', '=', 'persona_citas.ci')
            ->leftJoin('horarios', 'agendas.horario', '=', 'horarios.id_horario')
            ->where('fecha', $date)
            ->get();
        // Cache::put('citas' . $date, $cita_fecha);
        //}
        return $agenda;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function edit(agenda $agenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, agenda $agenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(agenda $agenda)
    {
        //
    }
    public static function buscar_consultorios(Request $request)
    {
        $agenda = $request['datos_agenda'];
        try {
            $configuracion = DB::table('cita_tiene_configuracions')
                ->select('*')
                ->where('fecha', '=', $agenda['fecha'])
                ->get();
        } catch (\Throwable $th) {
            return $th;
        }
        if (sizeof($configuracion) <= 0) {

            try {
                $configuracion = DB::table('configuracions')
                    ->select('*')
                    ->where('fecha_inicio', '<=', $agenda['fecha'])
                    ->where('fecha_final', '>=', $agenda['fecha'])
                    ->where('tipo', '=', 'permanente')
                    ->get();
            } catch (\Throwable $th) {
                return $th;
            }
        }
        if (sizeof($configuracion) == 1) {
            $conf = $configuracion[0];
            try {

                /*
                select horarios.sala, salas.descripcion from salas
                JOIN horarios on horarios.sala = salas.sala 
                LEFT join agendas on agendas.horario = horarios.id_horario and agendas.fecha = '2022-11-16'
                --RIGHT join salas on horarios.sala = salas.sala 
                where agendas.horario is NULL  --and agendas.consultorio =  horarios.sala
                group by horarios.sala, salas.descripcion 
                ORDER BY  horarios.sala, salas.descripcion
                */
                $salas = DB::table('salas')
                    //->select('salas.sala, salas.descripcion')
                    ->join('horarios', "horarios.sala",'=', "salas.sala")
                    ->leftJoin("agendas", function ($join) use ($agenda) {
                        $v = $agenda['fecha'];
                        $join->on("agendas.horario", "=", "horarios.id_horario");
                        $join->where('agendas.fecha','=',$v);
                      })
                    ->whereNull('agendas.horario')
                    ->groupBy('salas.sala', 'salas.descripcion')
                    ->select('salas.sala', 'salas.descripcion')
                    ->orderBy('salas.descripcion')
                    ->get();
            } catch (\Throwable $th) {
                return $th;
            }
            return $salas;
        }


        return $configuracion;
    }
    public static function dias_tiene_configuracion(){
        $date = date_create('', timezone_open('America/La_Paz'));
        $date = date_format($date, 'Y-m-d');
        $list_config = DB::table('cita_tiene_configuracions')
            ->select('*')
            ->leftJoin('configuracions', 'configuracions.id', '=', 'cita_tiene_configuracions.id')
            ->where('fecha', '>=', $date)
            ->where('atencion', '=', 'true')
            ->get();
        $verificar = false;
        
        $resp = [
            'dias_con_configuracion' => $list_config,
            //'verificar' => $verificar,
            'n' => count($list_config)
        ];
        return $resp;
        return $date;
    }
    public static function salas_por_fechas(){
        /*
select cita_tiene_configuracions.fecha, 
SUM(CASE WHEN  not agendas.consultorio IS NULL  THEN 1 ELSE 0 END) as uso, 
SUM(CASE WHEN agendas.consultorio IS NULL and not salas.id is null  THEN 1 ELSE 0 END) AS disponibles, 
count(salas.id) as total
--select cita_tiene_configuracions.fecha, horarios.id_horario, horarios.hora_inicio, horarios.sala, agendas.codigo_cita 
from cita_tiene_configuracions
left join salas on salas.id = cita_tiene_configuracions.id
left join horarios on horarios.sala = salas.sala
LEFT join agendas on  agendas.fecha = cita_tiene_configuracions.fecha and agendas.consultorio = salas.sala and agendas.horario = horarios.id_horario
--where not salas.id is null and 
--where cita_tiene_configuracions.fecha >= '2022-11-29'
GROUP by cita_tiene_configuracions.fecha
--having SUM(CASE WHEN agendas.consultorio IS NULL and not salas.id is null  THEN 1 ELSE 0 END)> 0
order by cita_tiene_configuracions.fecha
        */
        $list_config = DB::table('cita_tiene_configuracions')
        
        ->leftJoin('salas', function($join) {
            $join->on('cita_tiene_configuracions.id', '=','salas.id');

        })
        ->leftJoin('horarios', 'horarios.sala', '=','salas.sala')
        ->leftJoin("agendas", function ($join) {
            $join->on("agendas.horario", "=", "horarios.id_horario");
            $join->where('agendas.consultorio','=',DB::raw("salas.sala"));
            $join->where('agendas.fecha','=',DB::raw("cita_tiene_configuracions.fecha"));
            //$join->on()
          })
        //->where('cita_tiene_configuracions.fecha', '>=', '2022-11-16')
        ->groupBy('cita_tiene_configuracions.fecha' )
        //->havingRaw('SUM(CASE WHEN  not agendas.consultorio IS NULL  THEN 1 ELSE 0 END) = 0')
        ->selectRaw('cita_tiene_configuracions.fecha, 
        SUM(CASE WHEN  not agendas.consultorio is null  THEN 1 ELSE 0 END) as uso,
        SUM(CASE WHEN agendas.consultorio is null and not salas.id is null  THEN 1 ELSE 0 END) AS disponibles,
        count(salas.id) as total')
        ->get()
        ;
        return $list_config;
    }
}
/*
select cita_tiene_configuracions.fecha, 
SUM(CASE WHEN  not agendas.consultorio IS NULL  THEN 1 ELSE 0 END) as uso, 
SUM(CASE WHEN agendas.consultorio IS NULL and not salas.id is null  THEN 1 ELSE 0 END) AS disponibles, 
count(salas.id) as total
--select cita_tiene_configuracions.fecha, horarios.id_horario, horarios.hora_inicio, horarios.sala, agendas.codigo_cita 
from cita_tiene_configuracions
left join salas on salas.id = cita_tiene_configuracions.id 
left join horarios on horarios.sala = salas.sala
LEFT join agendas on  agendas.fecha = cita_tiene_configuracions.fecha and agendas.consultorio = salas.sala and agendas.horario = horarios.id_horario
--where not salas.id is null and 
--where cita_tiene_configuracions.fecha >= '2022-11-29'
GROUP by cita_tiene_configuracions.fecha
--having SUM(CASE WHEN agendas.consultorio IS NULL and not salas.id is null  THEN 1 ELSE 0 END)> 0
order by cita_tiene_configuracions.fecha


SELECT  * --cita_tiene_configuracions.fecha, horarios.id_horario, horarios.hora_inicio, horarios.sala, agendas.codigo_cita * --cita_tiene_configuracions.fecha 
from cita_tiene_configuracions
left join salas on salas.id = cita_tiene_configuracions.id

left join horarios on horarios.sala = salas.sala
where cita_tiene_configuracions.fecha = '2022-10-11'


select * 
from salas



*/