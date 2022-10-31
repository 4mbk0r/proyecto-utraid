<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

            $table->increments('id_horario');
            $table->time('hora_inicio');
            $table->time('hora_final');
            $table->integer('sala');
            $table->foreign('sala')->references('sala')->on('salas')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['hora_inicio', 'sala']);
        });

        for ($i=0; $i < 5; $i++) { 
            # code..
            $hora = [

                'sala' => $i+1,
                'hora_inicio' => '08:00:00',
                'hora_final' => '09:00:00',
            ];
            DB::table('horarios')->insert($hora);
            $hora = [
    
                'sala' => $i+1,
                'hora_inicio' => '09:00:00',
                'hora_final' => '10:00:00',
            ];
            DB::table('horarios')->insert($hora);
            $hora = [
    
                'sala' => $i+1,
                'hora_inicio' => '10:00:00',
                'hora_final' => '11:00:00',
            ];
            DB::table('horarios')->insert($hora);
            $hora = [
    
                'sala' => $i+1,
                'hora_inicio' => '11:00:00',
                'hora_final' => '12:00:00',
            ];
            DB::table('horarios')->insert($hora);
            $hora = [
    
                'sala' => $i+1,
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
            ];
            DB::table('horarios')->insert($hora);
            $hora = [
    
                'sala' => $i+1,
                'hora_inicio' => '13:30:00',
                'hora_final' => '14:30:00',
            ];
            DB::table('horarios')->insert($hora);
    
        }
                
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
