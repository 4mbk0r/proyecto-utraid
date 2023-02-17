<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAsignarConfigSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_config_salas', function (Blueprint $table) {
            $table->integer('id_conf');
            $table->integer('id_sala');
            //$table->integer('id_conf');
            $table->integer('id_conf_sala');
        });
        DB::statement(
            "ALTER TABLE asignar_config_salas ADD FOREIGN KEY (id_conf) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_config_salas ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_config_salas ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id) ON DELETE CASCADE"
        );
        for ($i = 1; $i <= 5; $i++) {
            $hora = [
                'id_sala' => $i,
                'id_conf_sala' => 1,
                'id_conf'=> 1
            ];
            DB::table('asignar_config_salas')->insert($hora);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignar_config_salas');
    }
}
