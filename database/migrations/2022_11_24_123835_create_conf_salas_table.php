<?php

use App\Models\Horario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conf_salas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_configuracion');
            $table->integer('id_sala');
            //$table->integer('id_horario');
            $table->string('id_institucion');
            $table->unique(['id_configuracion', 'id_sala',  'id_institucion']);
           
        });
        DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_configuracion) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        /*DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );*/
        DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_institucion) REFERENCES institucions(codigo) ON DELETE CASCADE"
        );
  
        # code...
        for ($j = 1; $j <= 5; $j++) {
            # code...
            $conf_salas = [

                //'sala' => $i+1,
                'id_configuracion' => '1',
                'id_institucion' => '01',
                'id_sala' => $j,
            ];
            DB::table('conf_salas')->insert($conf_salas);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conf_salas');
    }
}
