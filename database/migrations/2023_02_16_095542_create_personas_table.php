<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS unaccent');
        DB::statement('SET datestyle = SQL,YMD');

        Schema::create('personas', function (Blueprint $table) {
            $table->string('id')->unique();


            /*
                ap_paterno! ap_materno! nombre_de_registro
            
            */
            //$table->string('codigo')->unique();
            $table->string('ci');
            //$table->foreignId('nombre')->nullable()->index();
            $table->text('nombres');
            $table->text('ap_paterno', 100)->nullable();
            $table->text('ap_materno', 100)->nullable();
            $table->text('ap_casada', 100)->nullable();
            $table->text('celular', 100)->nullable();
            $table->text('correo', 100)->nullable();
            $table->text('expedido', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('direccion', 100)->nullable();
            $table->text('sexo', 50)->nullable();
            $table->text('nom_municipio', 50)->nullable();
            $table->text('nom_departamento', 50)->nullable();
            $table->boolean('register')->default(True);
            $table->unique(['ci', 'expedido']);

            //});
            /*$datos = [
                'ci' => '8340024',
                'nombres' => 'F',
                'ap_materno' => 'Condori',
                'ap_paterno' => 'Quispe',
                'ci' => '8340024',
                'expedido' => 'La Paz',
                
            ];
            DB::table('persona_citas')->insert($datos);*/
        });
        DB::statement("
            CREATE OR REPLACE FUNCTION generate_auto_increment(prefix text) RETURNS text AS $$
            DECLARE
            last_seq integer;
            new_seq integer;
            new_string text;
            BEGIN
            SELECT COALESCE(MAX(SUBSTRING(id FROM length(prefix) + 1)::integer), 0)
            INTO last_seq
            FROM personas
            WHERE id LIKE prefix || '%';
            
            new_seq := last_seq + 1;
            new_string := prefix || LPAD(new_seq::text, 2, '0');
            
            RETURN new_string;
            END;
            $$ LANGUAGE plpgsql;
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
