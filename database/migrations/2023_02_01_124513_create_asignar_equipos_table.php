
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAsignarEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_equipos', function (Blueprint $table) {
            $table->integer('id_usuario');
            $table->integer('id_equipo');
        });
        DB::statement(
            "ALTER TABLE asignar_equipos ADD FOREIGN KEY (id_usuario) REFERENCES users(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_equipos ADD FOREIGN KEY (id_equipo) REFERENCES equipos(id) ON DELETE CASCADE"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignar_equipos');
    }
}
