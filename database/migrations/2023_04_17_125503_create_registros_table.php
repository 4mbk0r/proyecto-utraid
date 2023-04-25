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
            $table->string('numero_formulario')->nullable();
            $table->string('id_registro')->nullable();
            $table->string('tipo_discapacidad')->nullable();
            $table->string('grado_discapacidad')->nullable();
            $table->string('porcentaje')->nullable();
            $table->string('id_persona');
            $table->unique(['fecha_registro', 'id_persona']);
        });
        DB::statement(
            "ALTER TABLE registros ADD FOREIGN KEY (id_persona) REFERENCES personas(id)"
        );
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
