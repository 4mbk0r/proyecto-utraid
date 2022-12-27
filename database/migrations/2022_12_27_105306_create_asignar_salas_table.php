<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAsignarSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_salas', function (Blueprint $table) {
            $table->increments('id');
        });
        DB::statement(
            "ALTER TABLE asignar_salas ADD FOREIGN KEY (id_horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_salas ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id) ON DELETE CASCADE"
        );

    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignar_salas');
    }
}
