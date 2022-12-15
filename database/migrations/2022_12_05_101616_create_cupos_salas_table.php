<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCuposSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /* Schema::create('cupos_salas', function (Blueprint $table) {
            $table->integer('id_cupo');
            $table->integer('id_horario');
        });
        DB::statement(
            "ALTER TABLE cupos_salas ADD FOREIGN KEY (id_horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE cupos_salas ADD FOREIGN KEY (id_cupo) REFERENCES cupos(id) ON DELETE CASCADE"
        );*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('cupos_salas');
    }
}
