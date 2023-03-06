<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDesignarEquipoLinealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designar_equipo_lineals', function (Blueprint $table) {
            $table->integer('id_equipo');
            $table->integer('id_conf');
            $table->integer('id_sala');
        });
        DB::statement(
            "ALTER TABLE designar_equipo_lineals ADD FOREIGN KEY (id_equipo) REFERENCES equipos(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE designar_equipo_lineals ADD FOREIGN KEY (id_conf) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE designar_equipo_lineals ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designar_equipo_lineals');
    }
}
