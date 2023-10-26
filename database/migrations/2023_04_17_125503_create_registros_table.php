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
        
        DB::statement("SET datestyle = 'ISO, DMY'");
        Schema::create('evaluacions', function (Blueprint $table) {
            $table->date('fecha_registro');
            $table->date('fecha_vigencia');
            $table->string('diagnostico')->nullable();
            $table->string('id_registro')->nullable();
            $table->string('tipo_discapacidad')->nullable();
            $table->string('grado_discapacidad')->nullable();
            $table->string('porcentaje')->nullable();
            $table->string('accede_carnet_discapacidad')->nullable();
            $table->string('id_persona');
            $table->unique(['fecha_registro', 'id_persona']);
        });
        DB::statement(
            "ALTER TABLE evaluacions ADD FOREIGN KEY (id_persona) REFERENCES personas(id)"
        );
        
    }   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluacions');
    }
}
