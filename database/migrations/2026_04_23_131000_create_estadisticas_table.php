<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->string('temporada');
            $table->unsignedInteger('puntos_totales')->default(0);
            $table->unsignedInteger('rebotes')->default(0);
            $table->unsignedInteger('asistencias')->default(0);
            $table->unsignedInteger('robos')->default(0);
            $table->unsignedInteger('rebotes_defensivos')->default(0);
            $table->unsignedInteger('rebotes_ofensivos')->default(0);
            $table->unsignedInteger('tapones')->default(0);
            $table->unsignedInteger('partidos_jugados')->default(0);
            $table->unsignedInteger('victorias')->default(0);
            $table->unsignedInteger('derrotas')->default(0);
            $table->timestamps();

            $table->unique(['equipo_id', 'temporada']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas');
    }
};
