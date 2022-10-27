<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->time('tiempo_inicio');
            $table->time('tiempo_final');
            $table->string('estado')->default('habilitado');
            $table->integer('sala');
            $table->foreign('sala')->references('sala')->on('salas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->unique(['tiempo_inicio', 'sala']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios');
    }
}
