<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDesignarEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designar_equipos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->integer('id_equipo');
            $table->unique(['fecha', 'id_equipo']);
        });
        DB::statement(
            "ALTER TABLE designar_equipos ADD FOREIGN KEY (fecha) REFERENCES calendarios(fecha)"
        );
        DB::statement(
            "ALTER TABLE designar_equipos ADD FOREIGN KEY (id_equipo) REFERENCES equipos(id)"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designar_equipos');
    }
}
