<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

class CreateFichasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_sala');
            $table->integer('id_horario');
            $table->integer('id_conf_sala');
            $table->date('fecha');
            $table->string('codigo');
            $table->unique(['id_sala', 'id_horario', 'id_conf_sala', 'fecha', 'codigo']);
            //$table->timestamps();
        });
        DB::statement(
            "ALTER TABLE fichas ADD FOREIGN KEY (id_sala) REFERENCES salas(id)"
        );
        DB::statement(
            "ALTER TABLE fichas ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id)"
        );
        DB::statement(
            "ALTER TABLE fichas ADD FOREIGN KEY (id_horario) REFERENCES horarios(id)"
        );
        DB::statement(
            "ALTER TABLE fichas ADD FOREIGN KEY (codigo) REFERENCES institucions(codigo) ON DELETE CASCADE"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichas');
    }
}
