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
        Schema::create('clasificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->nullOnDelete();
            $table->string('equipo_nombre');
            $table->string('categoria');
            $table->string('temporada');
            $table->unsignedInteger('posicion');
            $table->unsignedInteger('partidos_jugados')->default(0);
            $table->unsignedInteger('partidos_ganados')->default(0);
            $table->unsignedInteger('partidos_perdidos')->default(0);
            $table->unsignedInteger('puntos_favor')->default(0);
            $table->unsignedInteger('puntos_contra')->default(0);
            $table->unsignedInteger('puntos')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clasificaciones');
    }
};
