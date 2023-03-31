<?php

namespace App\Http\Controllers\Configuracion;

use App\Models\Calendario;
use App\Http\Controllers\Controller;
use App\Models\persona;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\Cloner\Data;


use Org_Heigl\Holidaychecker\Holidaychecker;
use Org_Heigl\Holidaychecker\HolidayIteratorFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpParser\Node\Expr\Cast\Object_;
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
            $datos = (array)$request['feriado'];
            //return $datos;
            DB::table("calendarios")->insert($datos);
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
    public static function excel_personal($request)
    {

        //return $request;
        $hearder_personas =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", "users")
            ->get();

        $hearder_nonull =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", "users")
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
            $verificar_datos = true;
            $valores = [];
            $hearder = [];
            foreach ($sheet->getRowIterator() as $row) {


                $f = new stdClass();
                $j = 0;
                //$error = false;
                foreach ($row->getCellIterator() as $cell) {

                    if ($i == 0) {
                        $valor_buscar = $cell->getValue();
                        //$verificar_datos = true;
                        /*foreach ($hearder_nonull as $x) {
                            if ($x === $valor_buscar) {
                            }
                        }*/
                        array_push($hearder,  trim($cell->getValue()));
                    } else {
                        $key = trim($hearder[$j]);

                        $f->$key =  trim($cell->getValue());
                        $value =  trim($cell->getValue());
                        if ($key ==  'establecimiento') {
                            $estableciento = DB::table('establecimientos')->where('nombre', '=', trim($f->establecimiento));
                            //$f->error = false;
                            $f->error_detalle = "";
                            if (!$estableciento->exists()) {
                                $verificar_datos = false;
                                //$f->error = true;
                                $f->error_detalle = 'no existe el estableciento';
                            }
                        }
                    }

                    $j++;
                    // Do something with the value
                }


                if ($i > 0) {

                    $rules = [
                        'nombres' => 'required',
                        'ci' => 'required'
                    ];
                    $messages = [
                        'nombres.required' => 'Se requiere nombres',
                        'ci.required' => 'Se requiere cedula de identidad',
                        /*'email.email' => 'Please enter a valid email address.',
                        'password.required' => 'Please enter a password.',
                        'password.min' => 'Your password must be at least 8 characters long.',*/
                    ];
                    //$resp = new Request((array)$f);
                    $validator = Validator::make(json_decode(json_encode($f), true), $rules);
                    if (!$validator->passes()) {
                        # code...
                        $verificar_datos = false;
                        $f->error_detalle = 'Se requieren datos';
                    }

                    $usuario_existen = db::table('users')
                        ->where('ci', '=', $f->ci)
                        ->where('expedido', '=', $f->expedido);
                    if (($usuario_existen && $usuario_existen->exists())) {
                        $verificar_datos = false;
                        $f->error_detalle = 'El Usuario ya existe editar de manera uniataria unitaria';
                    }
                    array_push($valores, $f);
                }
                $i++;
            }
            if ($verificar_datos) {
                foreach ($valores as $key => $value) {
                    # code...

                    // La validación fue exitosa
                    $query = db::table('users')
                        ->where('ci', '=', $value->ci)
                        ->where('expedido', '=', $value->expedido);


                    if (!($query && $query->exists())) {
                        $insertar = new stdClass();
                        foreach ($hearder_personas as $key => $valor) {
                            # code...
                            $vk = ($valor->nombre);

                            if (property_exists($value, $vk)) {
                                $insertar->$vk = $value->$vk;
                            } else {
                                $insertar->$vk = null;
                            }
                        }
                        $insertar->username = $insertar->ci;
                        $insertar->password = Hash::make($insertar->ci);
                        $u = (array)$insertar;
                        unset($u['id']);
                        unset($u['error']);
                        unset($u['error_detalle']);

                        //unset($u['register']);
                        $id = db::table('users')
                            ->insertGetId($u);
                        db::table('contratos')
                            ->insert(['id_usuario' => $u['username'], 'id_establecimiento' => $value->establecimiento]);
                    }

                    //array_push($r, $f);



                }
            }
            // Do something with the data in the sheet
            // ...
            return response()->json(['file' => $valores, 'header' => $verificar_datos, 'ca' => $validator->passes(),  'success' => true]);
            return $file;
            // guardar el archivo o hacer lo que sea necesario con él
            return response()->json(['message' => 'Archivo subido exitosamente']);
        } else {
            return response()->json(['message' => 'No se encontró ningún archivo'], 400);
        }
    }
    public static function personal_json(Request $request)
    {

        $resp = CalendarioController::validar_personal($request['archivo']);
        $verificar_datos =  $resp['verificar'];
        $valores =  $resp['datos'];
        if ($verificar_datos) {
            $hearder_personas =
                DB::table("information_schema.columns")
                ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
                ->where("table_name", "=", "users")
                ->get();
            foreach ($valores as $key => $value) {
                # code...
                $value = (object) $value;
                // La validación fue exitosa
                $query = db::table('users')
                    ->where('ci', '=', $value->ci)
                    ->where('expedido', '=', $value->expedido);


                if (!($query && $query->exists())) {
                    $insertar = new stdClass();
                    foreach ($hearder_personas as $key => $valor) {
                        # code...
                        $vk = ($valor->nombre);

                        if (property_exists($value, $vk)) {
                            $insertar->$vk = $value->$vk;
                        } else {
                            $insertar->$vk = null;
                        }
                    }
                    $insertar->username = $insertar->ci;
                    $insertar->password = Hash::make($insertar->ci);
                    $u = (array)$insertar;
                    unset($u['id']);
                    unset($u['error']);
                    unset($u['error_detalle']);

                    //unset($u['register']);
                    $id = db::table('users')
                        ->insertGetId($u);
                    db::table('contratos')
                        ->insert(['id_usuario' => $u['username'], 'id_establecimiento' => $value->establecimiento]);
                }

                //array_push($r, $f);



            }

            return ['verificar' => $verificar_datos, 'file' => $query];
        } else {
            return $resp;
        }
        //return $request;
    }
    public static function validar_personal(array $query)
    {

        $verificar_datos = true;
        foreach ($query as $key => $value) {
            # code...
            //$value = json_encode($value);
            $estableciento = DB::table('establecimientos')->where('nombre', '=', trim($value['establecimiento']));
            //$value['error'] = false;
            //$value->error_detalle = '';
            $query[$key]['error_detalle'] = '';
            if (!$estableciento->exists()) {
                $verificar_datos = false;
                //$value->error = true;
                $query[$key]['error_detalle']  = 'no existe el estableciento';
            }
            $rules = [
                'nombres' => 'required',
                'ci' => 'required'
            ];
            $messages = [
                'nombres.required' => 'Se requiere nombres',
                'ci.required' => 'Se requiere cedula de identidad',
                /*'email.email' => 'Please enter a valid email address.',
                    'password.required' => 'Please enter a password.',
                    'password.min' => 'Your password must be at least 8 characters long.',*/
            ];
            //$resp = new Request((array)$f);
            $validator = Validator::make((array)$value, $rules, $messages);
            if ($validator->fails()) {
                # code...
                $verificar_datos = false;
                $value['error_detalle'] = 'Se requieren datos';
            }
        }
        return ['verificar' => $verificar_datos, 'datos' => $query];
    }
    
}
