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
       /* Schema::create('cupos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_calendario');
            $table->integer('id_sala');
            //d$table->integer('id_horario');
            $table->string('id_institucion');
            $table->time('tiempo_apertura');
            $table->time('tiempo_cierre');
            $table->time('tiempo_descanso')->nullable();
            $table->integer('min_promedio_atencion');
            $table->unique(['id_calendario', 'id_sala', 'id_institucion']);
        });
        DB::statement(
            "ALTER TABLE cupos ADD FOREIGN KEY (id_calendario) REFERENCES calendarios(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE cupos ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE cupos ADD FOREIGN KEY (id_institucion) REFERENCES institucions(codigo) ON DELETE CASCADE"
        );*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('cupos');
    }
}
