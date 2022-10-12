<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCitaTieneConfiguracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cita_tiene_configuracions', function (Blueprint $table) {
            $table->date('fecha');
            $table->integer('id');
            $table->foreign('id')->references('id')->on('configuracions')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['fecha']);
        });
        $datos2 = [
           
            'fecha' => '2022-10-11',
            'id' => '1',
            
        ];
        DB::table('cita_tiene_configuracions')->insert($datos2);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cita_tiene_configuracions');
    }
}
