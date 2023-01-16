<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAsignarSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_salas', function (Blueprint $table) {
            $table->integer('id_sala');
            $table->integer('id_conf_sala');
            
        });
        DB::statement(
            "ALTER TABLE asignar_salas ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_salas ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id) ON DELETE CASCADE"
        );
        for ($i=1; $i <= 5 ; $i++) { 
            # code...
            $datos = [
                'id_sala' => $i,
                'id_conf_sala'=>1,
            ];
            DB::table('asignar_salas')->insert($datos);
        }
     
        
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignar_salas');
    }
}
