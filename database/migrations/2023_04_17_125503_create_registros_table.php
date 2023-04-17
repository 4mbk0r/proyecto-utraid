<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->date('fecha_registro');
            $table->string('numero_formulario');
            $table->string('id_registro');
            $table->string('tipo_discapacidad');
            $table->string('grado_discapacidad');
            $table->integer('porcentaje');
            $table->timestamps();
        });
        
        DB::statement('SET datestyle = SQL,DMY');
        
    }   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros');
    }
}
