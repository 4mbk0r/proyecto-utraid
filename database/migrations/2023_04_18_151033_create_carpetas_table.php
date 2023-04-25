<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCarpetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpetas', function (Blueprint $table) {
            $table->string('codigo')->unique();
            $table->integer('id_persona')->unique();
        });

        /*DB::statement(
            "ALTER TABLE carpetas ADD FOREIGN KEY (id_persona) REFERENCES personas(id)"
        );*/       
        
        /*DB::statement("
            CREATE OR REPLACE FUNCTION generate_auto_increment(prefix text) RETURNS text AS $$
            DECLARE
            last_seq integer;
            new_seq integer;
            new_string text;
            BEGIN
            SELECT COALESCE(MAX(SUBSTRING(codigo FROM length(prefix) + 1)::integer), 0)
            INTO last_seq
            FROM carpetas
            WHERE codigo LIKE prefix || '%';
            
            new_seq := last_seq + 1;
            new_string := prefix || LPAD(new_seq::text, 2, '0');
            
            RETURN new_string;
            END;
            $$ LANGUAGE plpgsql;
        ");*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carpetas');
    }
}
