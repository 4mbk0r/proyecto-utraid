<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->string('id');
            $table->integer('sala')->unique();
            $table->string('descripcion');
            $table->time('tiempo_apertura');
            $table->time('tiempo_cierre');
            $table->time('tiempo_descanso');
            $table->boolean('estado');
            $table->jsonb('horario');
            $table->foreign('id')->references('id')->on('configuracions')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['sala', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salas');
    }
}
