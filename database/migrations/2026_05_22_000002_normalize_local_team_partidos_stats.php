<?php

use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        // La localía ya no depende de si el equipo es Bellreguard.
        // La asignación de estadísticas se gestiona con estadisticas_equipo_id.
    }

    public function down(): void
    {
        //
    }
};
