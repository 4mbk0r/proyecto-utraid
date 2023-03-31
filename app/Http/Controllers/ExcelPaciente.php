<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use stdClass;
use Illuminate\Support\Facades\Validator;

class ExcelPaciente extends Controller
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
    public static function get_sheet(Request $request)
    {
        //return $request;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $objPHPExcel = IOFactory::load($file);
            $hojas = $objPHPExcel->getSheetCount();
            $resp = [];
            for ($i = 0; $i < $hojas; $i++) {
                $nombre_hoja = $objPHPExcel->getSheet($i)->getTitle();
                array_push($resp, ['id' => $i, 'nombre_sheet' => $nombre_hoja]);
            }
            return $resp;
        } else {
            return response()->json(['message' => 'No se encontró ningún archivo'], 400);
        }
    }
    public static function validate_date(Request $request)
    {

        $table = 'persona';
        $hearder =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", $table)
            ->get();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $objPHPExcel = IOFactory::load($file);

            //$hojas = $objPHPExcel->getSheetCount();
            //$resp = [];
            $sheet_id = json_decode($request->input('sheet'))->id;
            $sheet = $objPHPExcel->getSheet($sheet_id);
            $i = 0;
            $hearder_sheet = [];
            $r = [];
            foreach ($sheet->getRowIterator() as $row) {


                $f = new stdClass();
                $j = 0;
                foreach ($row->getCellIterator() as $cell) {

                    if ($i == 0) {
                        $valor_buscar = $cell->getValue();
                        array_push($hearder_sheet,  trim($cell->getValue()));
                    } else {
                        $key = $hearder_sheet[$j];
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
                    $validator = Validator::make(json_decode(json_encode($f), true), $rules);
                    if ($validator->passes()) {
                        // La validación fue exitosa
                        $query = db::table('personas')
                            ->where('ci', '=', $f->ci)
                            ->where('expedido', '=', $f->expedido)
                            ->get();
                        array_push($r, count($query));

                        if (count($query) == 0) {
                            $insertar = new stdClass();
                            foreach ($hearder as $key => $value) {
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
                        //array_push($r, $f);
                        // La validación falló, manejar el error
                    }
                }
                $i++;
            }

            return response()->json(['file' => $r, 'header' => $hearder, 'success' => true]);
            return $file;;
        } else {
            return response()->json(['message' => 'No se encontró ningún archivo'], 400);
        }
    }
}
