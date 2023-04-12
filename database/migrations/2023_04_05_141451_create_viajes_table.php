<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('viajes', function (Blueprint $table) {
            //$table->id();
            $table->integer('id_municipio');
            $table->integer('id_sala');
            $table->date('fecha');   
            $table->unique(['id_sala', 'id_municipio']);         
        });
        
        DB::statement(
            "ALTER TABLE viajes ADD FOREIGN KEY (id_municipio) REFERENCES municipios(id)"
        );
        DB::statement(
            "ALTER TABLE viajes ADD FOREIGN KEY (id_sala) REFERENCES salas(id)"
        );
       
           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viajes');
    }
}
