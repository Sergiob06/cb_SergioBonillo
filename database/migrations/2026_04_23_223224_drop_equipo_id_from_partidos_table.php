<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('partidos', function (Blueprint $table) {
            // eliminar foreign key primero
            $table->dropForeign(['equipo_id']);

            // luego eliminar columna
            $table->dropColumn('equipo_id');
        });
    }

    public function down()
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->unsignedBigInteger('equipo_id')->nullable();
        });
    }
};
