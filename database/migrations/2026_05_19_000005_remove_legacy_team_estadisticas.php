<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('estadisticas')
            ->whereNull('partido_id')
            ->delete();
    }

    public function down(): void
    {
        // Las estadísticas legacy por equipo no se restauran porque el dominio actual es por partido.
    }
};
