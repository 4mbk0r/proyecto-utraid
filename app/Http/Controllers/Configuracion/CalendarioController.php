<?php

namespace App\Http\Controllers\Configuracion;

use App\Models\Calendario;
use App\Http\Controllers\Controller;
use App\Models\persona;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\Cloner\Data;


use Org_Heigl\Holidaychecker\Holidaychecker;
use Org_Heigl\Holidaychecker\HolidayIteratorFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use stdClass;

class CalendarioController extends Controller
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
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function show(Calendario $calendario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendario $calendario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendario $calendario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendario $calendario)
    {
        //
    }
    public static function verificar_fecha(String $fecha)
    {
        try {
            $fecha_valida = DB::table('calendarios')
                ->select('*')
                //->leftJoin('configuracions', 'configuracions.id', '=', 'cita_tiene_configuracions.id')
                ->where('fecha', '>=', date($fecha))
                ->where('clase', '=', 'atencion')
                ->get();
            $verificar = false;
            $n = count($fecha_valida);
            if ($n == 0) {
                $verificar = true;
            }
            $resp = [
                'lista_fechas' => $fecha_valida,
                'verificar' => $verificar,
                'n' => $n
            ];
        } catch (\Throwable $th) {
            return response()->json(['error' => 'error al insertar datos'], 409);
        }

        return $resp;
    }

    public static function fechas_vigentes()
    {



        /***** */

        $sql = DB::table("calendarios")
            ->select(["calendarios.fecha", "calendarios.atencion", DB::raw("count(dar_citas.id_ficha) as nro_citas")])
            ->leftJoin("fichas", function ($join) {
                $join->on("fichas.fecha", "=", "calendarios.fecha");
            })
            ->leftJoin("dar_citas", function ($join) {
                $join->on("dar_citas.id_ficha", "=", "fichas.id");
            })
            ->where('calendarios.fecha', '>=', date('d-m-Y'))

            ->groupBy("calendarios.fecha", "calendarios.atencion")
            ->get();
        return $sql;

        //return $resp;
    }
    public static function fechas_feriados()
    {


        $sql = DB::table("calendarios")
            ->where('calendarios.fecha', '>=', date('d-m-Y'))
            ->where('atencion', '=', 'feriado')
            ->get();
        return $sql;

        //return $resp;
    }
    public static function add_feriados($request)
    {

        //return $request;
        if (DB::table("calendarios")->where('fecha', '=', $request['feriado']['fecha'])->exists()) {
            //return new Response(['message' => 'Datos dupliacados' ], 400);
            DB::table('designar_equipos')
                ->where('fecha', '=', $request['feriado']['fecha'])
                ->delete();

            DB::table('fichas')
                ->where('fecha', '=', $request['feriado']['fecha'])
                ->delete();
        } else {
            $datos = (array)$request['feriado'];
            //return $datos;
            DB::table("calendarios")->insert($datos);
        }


        $sql = DB::table("calendarios")
            ->where('calendarios.fecha', '>=', date('d-m-Y'))
            ->where('atencion', '=', 'feriado')
            ->get();
        return $sql;

        //return $resp;
    }
    public static function update_feriados($request)
    {

        DB::table("calendarios")
            ->where('fecha', '=', $request['fecha']['fecha'])
            ->update($request['fecha']);
        return $request;




        if (DB::table("calendarios")->where('fecha', '=', $request['feriado']['fecha'])->exists()) {
            return new Response(['message' => 'Datos dupliacados'], 400);
        } else {
            $datos = (array)$request['feriado'];
            //return $datos;
            DB::table("calendarios")->insert($datos);
        }


        $sql = DB::table("calendarios")
            ->where('calendarios.fecha', '>=', date('d-m-Y'))
            ->where('atencion', '=', 'feriado')
            ->get();
        return $sql;

        //return $resp;
    }
    public static function delete_feriados($request)
    {
        if (DB::table("calendarios")->where('fecha', '=', $request['fecha']['fecha'])->exists()) {
            DB::table("calendarios")->where('fecha', '=', $request['fecha']['fecha'])
                ->delete();
        } else {
            return new Response(['message' => 'no se lo tiene registrado'], 400);
            /*
            $datos = (array)$request['fecha'];
            //return $datos;
            DB::table("calendarios")->insert($datos);*/
        }

        return $request;
    }
    public static function archivo_excel($request)
    {


        $hearder_personas =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", "personas")
            ->get();

        $hearder_nonull =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", "personas")
            ->where("is_nullable", "=", "YES")

            ->get();



        //return $hearder_personas;
        //return $request->file('file');
        if ($request->hasFile('file')) {


            $file = $request->file('file');

            // Use IOFactory to load the Excel file
            $spreadsheet = IOFactory::load($file);

            $r = [];
            // Get the first sheet in the workbook
            $sheet = $spreadsheet->getActiveSheet();


            //return $sheet[0];
            $i = 0;
            $hearder = [];
            foreach ($sheet->getRowIterator() as $row) {


                $f = new stdClass();
                $j = 0;
                foreach ($row->getCellIterator() as $cell) {

                    if ($i == 0) {
                        $valor_buscar = $cell->getValue();
                        foreach ($hearder_nonull as $x) {
                            if ($x === $valor_buscar) {
                            }
                        }
                        array_push($hearder,  trim($cell->getValue()));
                    } else {
                        $key = $hearder[$j];
                        $f->$key =  trim($cell->getValue());
                        $value =  trim($cell->getValue());
                    }
                    $j++;
                    // Do something with the value
                }

                if ($i > 0) {
                    $rules = [
                        'nombres' => 'required',
                        'ci' => 'required'
                    ];
                    //$resp = new Request((array)$f);
                    $validator = Validator::make((array)$f, $rules);
                    if ($validator->passes()) {
                        // La validación fue exitosa
                        $query = db::table('personas')
                            ->where('ci', '=', $f->ci)
                            ->where('expedido', '=', $f->expedido);


                        if (!($query && $query->exists())) {
                            $insertar = new stdClass();
                            foreach ($hearder_personas as $key => $value) {
                                # code...
                                $vk = ($value->nombre);

                                if (property_exists($f, $vk)) {
                                    $insertar->$vk = $f->$vk;
                                } else {
                                    $insertar->$vk = null;
                                }
                            }
                            $u = (array)$insertar;
                            unset($u['id']);
                            unset($u['register']);
                            $query = db::table('personas')
                                ->insert($u);
                        }
                        //array_push($r, $f);


                    } else {
                        array_push($r, $f);
                        // La validación falló, manejar el error
                    }
                }
                $i++;
            }
            // Do something with the data in the sheet
            // ...
            return response()->json(['file' => $hearder_personas, 'header' => $hearder, 'success' => true]);
            return $file;
            // guardar el archivo o hacer lo que sea necesario con él
            return response()->json(['message' => 'Archivo subido exitosamente']);
        } else {
            return response()->json(['message' => 'No se encontró ningún archivo'], 400);
        }
    }
}
