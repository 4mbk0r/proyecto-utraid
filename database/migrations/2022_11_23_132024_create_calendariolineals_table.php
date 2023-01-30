<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCalendariolinealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $base = 'calendariolineals';
        Schema::create($base, function (Blueprint $table) {
            $table->increments('id')->start_from(0);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->integer('historial');
            $table->integer('id_configuracion');
            //$table->unique(['fecha_inicio', 'fecha_final']);
            $table->boolean('principal')->default(false);
        });
        DB::statement(
            "ALTER TABLE " . $base . " ADD FOREIGN KEY (id_configuracion) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        $datos = [
            'fecha_inicio' => '01-11-2022',
            'fecha_final' => '30-12-9999',
            'historial' => '0',
            'id_configuracion' => 1,
            'principal' => true
        ];
        DB::table($base)->insert($datos);
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendariolineals');
    }
}
