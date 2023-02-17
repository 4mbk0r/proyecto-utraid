<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAsignarHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_horarios', function (Blueprint $table) {
            $table->integer('id_horario');
            $table->integer('id_conf_sala');
        });
        DB::statement(
            "ALTER TABLE asignar_horarios ADD FOREIGN KEY (id_horario) REFERENCES horarios(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_horarios ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id) ON DELETE CASCADE"
        );
        for ($i = 1; $i <= 7; $i++) {
            $hora = [
                'id_horario' => $i,
                'id_conf_sala' => 1
            ];
            DB::table('asignar_horarios')->insert($hora);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignar_horarios');
    }
}
