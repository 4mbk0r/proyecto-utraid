<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('cargos', function (Blueprint $table) {
            $table->string('cargo')->primary();
            $table->boolean('servicio')->default(0);
            $table->boolean('administrativo')->default(0);
            
        });
    
        $datos = [
            'cargo' => 'Admin',
        ];
        DB::table('cargos')->insert($datos);
        $datos = [
            'cargo' => 'Medico General',
            'servicio' => true
        ];
        DB::table('cargos')->insert($datos);
        $datos = [
            'cargo' => 'Trabajo Social',
            'servicio' => true
        ];
        DB::table('cargos')->insert($datos);
        $datos = [
            'cargo' => 'Psicologo',
            'servicio' => true
        ];
        DB::table('cargos')->insert($datos);
        $datos = [
            'cargo' => 'Operador Terapético',
            'servicio' => true
        ];
        DB::table('cargos')->insert($datos);
        $datos = [
            'cargo' => 'Secretaria',
            'servicio' => false,
            'administrativo' => true
        ];
        DB::table('cargos')->insert($datos);
        $datos = [
            'cargo' => 'recepcionista',
            'servicio' => false,
            'administrativo' => true
        ];
        DB::table('cargos')->insert($datos);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cargos');
    }
}
