<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCalendariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET datestyle = 'ISO, DMY'");
        
        Schema::create('calendarios', function (Blueprint $table) {
            //$table->increments('id');
            $table->date('fecha')->primary();
            $table->string('codigo')->default('01');
            $table->string('atencion')->default('atencion');
            $table->string('descripcion')->default('');
            $table->string('repeticion')->nullable();
            //$table->unique(['fecha', 'codigo']);
        });
        DB::statement(
            "ALTER TABLE calendarios ADD FOREIGN KEY (codigo) REFERENCES institucions(codigo) ON DELETE CASCADE"
        );
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendarios');
    }
}
