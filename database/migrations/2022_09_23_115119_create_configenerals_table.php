<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configenerals', function (Blueprint $table) {

            $table->date('fecha_config');
            $table->date('n_sala');
            $table->time('fin_atencion')->nullable();
            $table->time('inicio_atencion')->nullable();
            $table->primary(['fecha_config']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configenerals');
    }
}
