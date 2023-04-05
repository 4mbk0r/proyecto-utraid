<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('departamento');
            $table->string('municipio');
        });

        $municipio  = array(
            0 => array('departamento' => 'LA PAZ', 'municipio' => 'ACHACACHI', 'id' => '20201'),
            1 => array('departamento' => 'LA PAZ', 'municipio' => 'ACHOCALLA', 'id' => '20104'),
            2 => array('departamento' => 'LA PAZ', 'municipio' => 'ALTO BENI', 'id' => '22002'),
            3 => array('departamento' => 'LA PAZ', 'municipio' => 'ANCORAIMES', 'id' => '20202'),
            4 => array('departamento' => 'LA PAZ', 'municipio' => 'APOLO', 'id' => '20701'),
            5 => array('departamento' => 'LA PAZ', 'municipio' => 'AUCAPATA', 'id' => '20503'),
            6 => array('departamento' => 'LA PAZ', 'municipio' => 'AYATA', 'id' => '20502'),
            7 => array('departamento' => 'LA PAZ', 'municipio' => 'AYO AYO', 'id' => '21303'),
            8 => array('departamento' => 'LA PAZ', 'municipio' => 'BATALLAS', 'id' => '21203'),
            9 => array('departamento' => 'LA PAZ', 'municipio' => 'CAIROMA', 'id' => '20905'),
            10 => array('departamento' => 'LA PAZ', 'municipio' => 'CAJUATA', 'id' => '21003'),
            11 => array('departamento' => 'LA PAZ', 'municipio' => 'CALACOTO', 'id' => '20303'),
            12 => array('departamento' => 'LA PAZ', 'municipio' => 'CALAMARCA', 'id' => '21304'),
            13 => array('departamento' => 'LA PAZ', 'municipio' => 'CAQUIAVIRI', 'id' => '20302'),
            14 => array('departamento' => 'LA PAZ', 'municipio' => 'CARANAVI', 'id' => '22001'),
            15 => array('departamento' => 'LA PAZ', 'municipio' => 'CATACORA', 'id' => '21902'),
            16 => array('departamento' => 'LA PAZ', 'municipio' => 'CHACARILLA', 'id' => '21803'),
            17 => array('departamento' => 'LA PAZ', 'municipio' => 'CHARAÑA', 'id' => '20305'),
            18 => array('departamento' => 'LA PAZ', 'municipio' => 'CHUA COCANI', 'id' => '20203'),
            19 => array('departamento' => 'LA PAZ', 'municipio' => 'CHULUMANI (V. DE LA LIBERTAD)', 'id' => '21101'),
            20 => array('departamento' => 'LA PAZ', 'municipio' => 'CHUMA', 'id' => '20501'),
            21 => array('departamento' => 'LA PAZ', 'municipio' => 'COLLANA', 'id' => '21307'),
            22 => array('departamento' => 'LA PAZ', 'municipio' => 'COLQUENCHA', 'id' => '21306'),
            23 => array('departamento' => 'LA PAZ', 'municipio' => 'COLQUIRI', 'id' => '21004'),
            24 => array('departamento' => 'LA PAZ', 'municipio' => 'COMANCHE', 'id' => '20304'),
            25 => array('departamento' => 'LA PAZ', 'municipio' => 'COMBAYA', 'id' => '20605'),
            26 => array('departamento' => 'LA PAZ', 'municipio' => 'COPACABANA', 'id' => '21701'),
            27 => array('departamento' => 'LA PAZ', 'municipio' => 'CORIPATA', 'id' => '21402'),
            28 => array('departamento' => 'LA PAZ', 'municipio' => 'CORO CORO', 'id' => '20301'),
            29 => array('departamento' => 'LA PAZ', 'municipio' => 'COROICO', 'id' => '21401'),
            30 => array('departamento' => 'LA PAZ', 'municipio' => 'CURVA', 'id' => '21602'),
            31 => array('departamento' => 'LA PAZ', 'municipio' => 'DESAGUADERO', 'id' => '20804'),
            32 => array('departamento' => 'LA PAZ', 'municipio' => 'EL ALTO', 'id' => '20105'),
            33 => array('departamento' => 'LA PAZ', 'municipio' => 'ESCOMA', 'id' => '20405'),
            34 => array('departamento' => 'LA PAZ', 'municipio' => 'GRAL. J.J. PEREZ  (CHARAZANI)', 'id' => '21601'),
            35 => array('departamento' => 'LA PAZ', 'municipio' => 'GUANAY', 'id' => '20602'),
            36 => array('departamento' => 'LA PAZ', 'municipio' => 'GUAQUI', 'id' => '20802'),
            37 => array('departamento' => 'LA PAZ', 'municipio' => 'HUARINA', 'id' => '20204'),
            38 => array('departamento' => 'LA PAZ', 'municipio' => 'HUATAJATA', 'id' => '20206'),
            39 => array('departamento' => 'LA PAZ', 'municipio' => 'HUMANATA', 'id' => '20404'),
            40 => array('departamento' => 'LA PAZ', 'municipio' => 'ICHOCA', 'id' => '21005'),
            41 => array('departamento' => 'LA PAZ', 'municipio' => 'INQUISIVI', 'id' => '21001'),
            42 => array('departamento' => 'LA PAZ', 'municipio' => 'IRUPANA (VILLA DE LANZA)', 'id' => '21102'),
            43 => array('departamento' => 'LA PAZ', 'municipio' => 'IXIAMAS', 'id' => '21501'),
            44 => array('departamento' => 'LA PAZ', 'municipio' => 'JESUS DE MACHACA', 'id' => '20806'),
            45 => array('departamento' => 'LA PAZ', 'municipio' => 'LA ASUNTA', 'id' => '21105'),
            46 => array('departamento' => 'LA PAZ', 'municipio' => 'LA PAZ', 'id' => '20101'),
            47 => array('departamento' => 'LA PAZ', 'municipio' => 'LAJA', 'id' => '21202'),
            48 => array('departamento' => 'LA PAZ', 'municipio' => 'LICOMA PAMPA', 'id' => '21006'),
            49 => array('departamento' => 'LA PAZ', 'municipio' => 'LURIBAY', 'id' => '20901'),
            50 => array('departamento' => 'LA PAZ', 'municipio' => 'MALLA', 'id' => '20904'),
            51 => array('departamento' => 'LA PAZ', 'municipio' => 'MAPIRI', 'id' => '20607'),
            52 => array('departamento' => 'LA PAZ', 'municipio' => 'MECAPACA', 'id' => '20103'),
            53 => array('departamento' => 'LA PAZ', 'municipio' => 'MOCOMOCO', 'id' => '20402'),
            54 => array('departamento' => 'LA PAZ', 'municipio' => 'NAZACARA DE PACAJES', 'id' => '20307'),
            55 => array('departamento' => 'LA PAZ', 'municipio' => 'PALCA', 'id' => '20102'),
            56 => array('departamento' => 'LA PAZ', 'municipio' => 'PALOS BLANCOS', 'id' => '21104'),
            57 => array('departamento' => 'LA PAZ', 'municipio' => 'PAPEL PAMPA', 'id' => '21802'),
            58 => array('departamento' => 'LA PAZ', 'municipio' => 'PATACAMAYA', 'id' => '21305'),
            59 => array('departamento' => 'LA PAZ', 'municipio' => 'PELECHUCO', 'id' => '20702'),
            60 => array('departamento' => 'LA PAZ', 'municipio' => 'PUCARANI', 'id' => '21201'),
            61 => array('departamento' => 'LA PAZ', 'municipio' => 'PUERTO ACOSTA', 'id' => '20401'),
            62 => array('departamento' => 'LA PAZ', 'municipio' => 'PUERTO CARABUCO', 'id' => '20403'),
            63 => array('departamento' => 'LA PAZ', 'municipio' => 'PUERTO PEREZ', 'id' => '21204'),
            64 => array('departamento' => 'LA PAZ', 'municipio' => 'QUIABAYA', 'id' => '20604'),
            65 => array('departamento' => 'LA PAZ', 'municipio' => 'QUIME', 'id' => '21002'),
            66 => array('departamento' => 'LA PAZ', 'municipio' => 'SAN ANDRES DE MACHACA', 'id' => '20805'),
            67 => array('departamento' => 'LA PAZ', 'municipio' => 'SAN BUENA VENTURA', 'id' => '21502'),
            68 => array('departamento' => 'LA PAZ', 'municipio' => 'SAN PEDRO DE CURAHUARA', 'id' => '21801'),
            69 => array('departamento' => 'LA PAZ', 'municipio' => 'SAN PEDRO DE TIQUINA', 'id' => '21702'),
            70 => array('departamento' => 'LA PAZ', 'municipio' => 'SANTIAGO DE CALLAPA', 'id' => '20308'),
            71 => array('departamento' => 'LA PAZ', 'municipio' => 'SANTIAGO DE HUATA', 'id' => '20205'),
            72 => array('departamento' => 'LA PAZ', 'municipio' => 'SANTIAGO DE MACHACA', 'id' => '21901'),
            73 => array('departamento' => 'LA PAZ', 'municipio' => 'SAPAHAQUI', 'id' => '20902'),
            74 => array('departamento' => 'LA PAZ', 'municipio' => 'SICA SICA (VILLA AROMA)', 'id' => '21301'),
            75 => array('departamento' => 'LA PAZ', 'municipio' => 'SORATA', 'id' => '20601'),
            76 => array('departamento' => 'LA PAZ', 'municipio' => 'TACACOMA', 'id' => '20603'),
            77 => array('departamento' => 'LA PAZ', 'municipio' => 'TARACO', 'id' => '20807'),
            78 => array('departamento' => 'LA PAZ', 'municipio' => 'TEOPONTE', 'id' => '20608'),
            79 => array('departamento' => 'LA PAZ', 'municipio' => 'TIAHUANACU', 'id' => '20803'),
            80 => array('departamento' => 'LA PAZ', 'municipio' => 'TIPUANI', 'id' => '20606'),
            81 => array('departamento' => 'LA PAZ', 'municipio' => 'TITO YUPANQUI (PARQUIPUJIO)', 'id' => '21703'),
            82 => array('departamento' => 'LA PAZ', 'municipio' => 'UMALA', 'id' => '21302'),
            83 => array('departamento' => 'LA PAZ', 'municipio' => 'VIACHA', 'id' => '20801'),
            84 => array('departamento' => 'LA PAZ', 'municipio' => 'WALDO BALLIVIAN', 'id' => '20306'),
            85 => array('departamento' => 'LA PAZ', 'municipio' => 'YACO', 'id' => '20903'),
            86 => array('departamento' => 'LA PAZ', 'municipio' => 'YANACACHI', 'id' => '21103'),
        );
        foreach($municipio as $x) {
            foreach($x as $clave => $valor) {
                $x[$clave] = trim($valor);
            }
            DB::table('municipios')->insert($x);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios');
    }
}
