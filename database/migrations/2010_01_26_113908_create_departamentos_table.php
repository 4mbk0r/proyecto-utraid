<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->string('abreviado');
            $table->string('departamento')->primary();
            $table->string('codigo_ine');
        });
        $departamento = array(
            array("abreviado"=>"CH","departamento"=>"CHUQUISACA","codigo_ine"=>"1"),
            array("abreviado"=>"LP","departamento"=>"LA PAZ","codigo_ine"=>"2"),
            array("abreviado"=>"CB","departamento"=>"COCHABAMBA","codigo_ine"=>"3"),
            array("abreviado"=>"OR","departamento"=>"ORURO","codigo_ine"=>"4"),
            array("abreviado"=>"PT","departamento"=>"POTOSI","codigo_ine"=>"5"),
            array("abreviado"=>"TJ","departamento"=>"TARIJA","codigo_ine"=>"6"),
            array("abreviado"=>"SC","departamento"=>"SANTA CRUZ","codigo_ine"=>"7"),
            array("abreviado"=>"BE","departamento"=>"BENI","codigo_ine"=>"8"),
            array("abreviado"=>"PD","departamento"=>"PANDO","codigo_ine"=>"9"),
        );
        
        foreach ($departamento as $key => $value) {
            # code...
            DB::table('departamentos')->insert($value);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departamentos');
    }
}
