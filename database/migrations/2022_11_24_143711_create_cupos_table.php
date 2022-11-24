<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCuposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_calendario');
            $table->integer('id_sala');
            $table->integer('id_horario');
            $table->unique(['id_calendario', 'id_sala', 'id_horario']);
        });
        DB::statement(
            "ALTER TABLE cupos ADD FOREIGN KEY (id_calendario) REFERENCES calendarios(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE cupos ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE cupos ADD FOREIGN KEY (id_horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cupos');
    }
}
