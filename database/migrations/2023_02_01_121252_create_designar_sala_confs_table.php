<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDesignarSalaConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designar_sala_confs', function (Blueprint $table) {
            $table->integer('id_conf');
            $table->integer('id_asignacion');
        });
        DB::statement(
            "ALTER TABLE designar_sala_confs ADD FOREIGN KEY (id_conf) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE designar_sala_confs ADD FOREIGN KEY (id_asignacion) REFERENCES asignar_config_salas(id) ON DELETE CASCADE"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designar_sala_confs');
    }
}
