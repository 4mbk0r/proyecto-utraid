<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


use Illuminate\Support\Facades\DB;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->date('fecha');
            $table->text('ci', 20);
            $table->text('tipo_cita', 20)->nullable();;
            $table->text('se_presento', 20)->nullable();;
            $table->text('observacion')->nullable();;
            $table->integer('consultorio');
            $table->integer('horario');
            $table->integer('calendario');
            $table->text('lugar')->nullable();;
            //$table->primary(['fecha', 'hora_inicio', 'equipo']);
            //$table->foreign('ci')->references('ci')->on('persona_citas')->onDelete('cascade')->onUpdate('cascade');
            
        });
        DB::statement(
            "ALTER TABLE citas ADD FOREIGN KEY (horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE citas ADD FOREIGN KEY (consultorio) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE citas ADD FOREIGN KEY (calendario) REFERENCES calendarios(id) ON DELETE CASCADE"
        );
        /*$base  = array(
            0 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '12701633',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            1 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '4262589',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            2 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '9871228',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            3 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '3482951',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            4 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '6955203',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            5 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9908315',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            6 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '13926157',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'NO',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            7 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '13438149',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            8 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '12459063',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            9 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '14028307',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            10 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '16956013',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            11 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '7076431',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            12 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '3356871',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'NO',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            13 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '2557647',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            14 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '14977140',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            15 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '77711991',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            16 => array(
                'fecha' => '11/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '3480069',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            17 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '8447387',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            18 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2234762',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            19 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '13280138',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            20 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '6132983',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            21 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '4783155',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            22 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9184054',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            23 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '16940586',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            24 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '77524051',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            25 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2523741',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            26 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '5958254',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            27 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2467241',
                'tipo_cita' => 'NUEVO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            28 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '14702848',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            29 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '10001125',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            30 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '13152662',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => 'SI',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            31 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '2608385',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            32 => array(
                'fecha' => '12/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '8445171',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            33 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '5690303',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            34 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2525966',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            35 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '4831012',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            36 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '12540670',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            37 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '4870111',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            38 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '3499123',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            39 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '16095509',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            40 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '7074019',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            41 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '14482398',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            42 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '3485868',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            43 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '9909477',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            44 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '16098999',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            45 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '10035434',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            46 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '609449',
                'tipo_cita' => '',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            47 => array(
                'fecha' => '13/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '4849192',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            48 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '6106558',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            49 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '6946300',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            50 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '6035113',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            51 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2417030',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            52 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '100059281',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            53 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9873348',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            54 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2702481',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            55 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '9883693',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            56 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '4905811',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            57 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4850491',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            58 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4885186',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            59 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '6191955',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            60 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4904182',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            61 => array(
                'fecha' => '14/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '14108230',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            62 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '9986970',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            63 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '5948229',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            64 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9172993',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            65 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '5948212',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            66 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '13246129',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            67 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '4801430',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            68 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '8295752',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            69 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '6924904',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            70 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '3458696',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            71 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '2531614',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            72 => array(
                'fecha' => '18/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '8311918',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            73 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '10923435',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            74 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2352741',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            75 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2287692',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            76 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '88366418',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            77 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '12360044',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            78 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '2475734',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            79 => array(
                'fecha' => '19/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '3390359',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            80 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '15046703',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            81 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '11097603',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            82 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '7000602',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            83 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '66949694',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            84 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '6983553',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            85 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '8376257',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            86 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '6862625',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            87 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '4318024',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            88 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '8415549',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            89 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '9147549',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            90 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '8382733',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            91 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4897256',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            92 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '9081102',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            93 => array(
                'fecha' => '20/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '6143621',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            94 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '3391358',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            95 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '10078947',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            96 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2207070',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            97 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '14480885',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            98 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '8324855',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            99 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '6194914',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            100 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '9908212',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            101 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '4971978',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            102 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '1548483',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            103 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '8469203',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            104 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4913568',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            105 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '14429487',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            106 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '3376512',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            107 => array(
                'fecha' => '21/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '9868735',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            108 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '10020811',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            109 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '10005782',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            110 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2361686',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            111 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '8466565',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            112 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2510176',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            113 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '3347523',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            114 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '6756880',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            115 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '14999510',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            116 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '6898921',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            117 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '2706468',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            118 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '4313679',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            119 => array(
                'fecha' => '22/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '2443727',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            120 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '4958385',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            121 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '12861390',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            122 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '4946705',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            123 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '4850356',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            124 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '10920622',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            125 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2602493',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            126 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2715581',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            127 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2222398',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            128 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '4330986',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            129 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '6115778',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            130 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '13149480',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            131 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '13761187',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            132 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '13761186',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            133 => array(
                'fecha' => '25/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '8445522',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            134 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '10953472',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            135 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '16512474',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            136 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '13437529',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            137 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '3374835',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            138 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '13148688',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            139 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '4250375',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            140 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2709603',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            141 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '11110103',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            142 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4969298',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            143 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '2537479',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            144 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '10091400',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            145 => array(
                'fecha' => '26/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '2532982',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            146 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '3388474',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            147 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '4917097',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            148 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '6150600',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            149 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '14397763',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            150 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '3463695',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            151 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '6009031',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            152 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '9256632',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            153 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '9210091',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            154 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '1257323',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            155 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4277015',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            156 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '3397414',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            157 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4369856',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            158 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '2490320',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            159 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '6151960',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            160 => array(
                'fecha' => '27/04/2022',
                'hora_inicio' => '13:00:00',
                'hora_final' => '14:00:00',
                'ci' => '9154414',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            161 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '9102714',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            162 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '12541921',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            163 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '9246250',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            164 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '15009541',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            165 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '3438821',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            166 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2703430',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            167 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '15009547',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            168 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '5576489',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            169 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '5226229',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            170 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2387820',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            171 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '9176065',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            172 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '8351753',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            173 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '9861136',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            174 => array(
                'fecha' => '28/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '13246826',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            175 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '497455',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            176 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '13087329',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            177 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '423609',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            178 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9175104',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            179 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '7093845',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            180 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9090720',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            181 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '855490',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            182 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '13407344',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            183 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '12862303',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            184 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '15223187',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            185 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '6802453',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            186 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '13120641',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            187 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '7058410',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            188 => array(
                'fecha' => '29/04/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '13761120',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            189 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '13056451',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            190 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '8435812',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            191 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '10090948',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            192 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '8432202',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            193 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '12830040',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            194 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '3473285',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            195 => array(
                'fecha' => '03/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '8325820',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            196 => array(
                'fecha' => '04/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '14296547',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            197 => array(
                'fecha' => '04/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2223142',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            198 => array(
                'fecha' => '04/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2640367',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            199 => array(
                'fecha' => '04/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '2056390',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            200 => array(
                'fecha' => '04/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '7011592',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            201 => array(
                'fecha' => '05/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2661048',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            202 => array(
                'fecha' => '05/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '13887433',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            203 => array(
                'fecha' => '05/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2612194',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            204 => array(
                'fecha' => '05/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4802182',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            205 => array(
                'fecha' => '05/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4761261',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '5',
                'lugar' => 'CENTRO'
            ),
            206 => array(
                'fecha' => '06/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '13181746',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            207 => array(
                'fecha' => '06/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '8318389',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            208 => array(
                'fecha' => '06/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '9908302',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            209 => array(
                'fecha' => '06/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '9218678',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            210 => array(
                'fecha' => '06/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '6088048',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '5',
                'lugar' => 'CENTRO'
            ),
            211 => array(
                'fecha' => '09/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '13926157',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            212 => array(
                'fecha' => '09/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2615919',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            213 => array(
                'fecha' => '09/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '16668270',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            214 => array(
                'fecha' => '09/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '10905924',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            215 => array(
                'fecha' => '09/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '2461998',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '5',
                'lugar' => 'CENTRO'
            ),
            216 => array(
                'fecha' => '10/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '6758358',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            217 => array(
                'fecha' => '10/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '2325289',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            218 => array(
                'fecha' => '10/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '6029933',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '3',
                'lugar' => 'CENTRO'
            ),
            219 => array(
                'fecha' => '10/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '9219320',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '4',
                'lugar' => 'CENTRO'
            ),
            220 => array(
                'fecha' => '10/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4760467',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '5',
                'lugar' => 'CENTRO'
            ),
            221 => array(
                'fecha' => '11/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2633161',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            222 => array(
                'fecha' => '11/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '4771180',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            223 => array(
                'fecha' => '11/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '444305-1H',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '2',
                'lugar' => 'CENTRO'
            ),
            224 => array(
                'fecha' => '11/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '16906510',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            225 => array(
                'fecha' => '11/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4375817',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            226 => array(
                'fecha' => '11/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '6119499',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            227 => array(
                'fecha' => '12/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '4283651',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            228 => array(
                'fecha' => '12/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '13640310',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            229 => array(
                'fecha' => '12/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '6929461',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            230 => array(
                'fecha' => '12/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '13681000',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            231 => array(
                'fecha' => '12/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '2510176',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            232 => array(
                'fecha' => '13/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '3401027',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            233 => array(
                'fecha' => '13/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '9120497',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            234 => array(
                'fecha' => '13/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '3994085',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            235 => array(
                'fecha' => '13/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '4287141',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            236 => array(
                'fecha' => '13/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4321760',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            237 => array(
                'fecha' => '16/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '9911115',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            238 => array(
                'fecha' => '16/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '6003980',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            239 => array(
                'fecha' => '16/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '3385699',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            240 => array(
                'fecha' => '16/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '6865865',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            241 => array(
                'fecha' => '16/05/2022',
                'hora_inicio' => '12:30:00',
                'hora_final' => '13:30:00',
                'ci' => '4765861',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            242 => array(
                'fecha' => '17/05/2022',
                'hora_inicio' => '08:30:00',
                'hora_final' => '09:30:00',
                'ci' => '2371409',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            243 => array(
                'fecha' => '17/05/2022',
                'hora_inicio' => '09:30:00',
                'hora_final' => '10:30:00',
                'ci' => '8335267',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            244 => array(
                'fecha' => '17/05/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '9924604',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            245 => array(
                'fecha' => '17/05/2022',
                'hora_inicio' => '11:30:00',
                'hora_final' => '12:30:00',
                'ci' => '2366270',
                'tipo_cita' => 'NUEVO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
            246 => array(
                'fecha' => '08/08/2022',
                'hora_inicio' => '10:30:00',
                'hora_final' => '11:30:00',
                'ci' => '2682127',
                'tipo_cita' => 'RECALIFICADO',
                'se_presento' => '',
                'observacion' => '',
                'equipo' => '1',
                'lugar' => 'CENTRO'
            ),
        );        
        foreach ($base as $key => $value) {
            # code...

            DB::table('citas')->insert(
                $value
            );
        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
}