<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (!Schema::hasColumn('partidos', 'puntos_local')) {
                $table->unsignedInteger('puntos_local')->nullable()->after('lugar');
            }

            if (!Schema::hasColumn('partidos', 'puntos_visitante')) {
                $table->unsignedInteger('puntos_visitante')->nullable()->after('puntos_local');
            }
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (Schema::hasColumn('partidos', 'puntos_visitante')) {
                $table->dropColumn('puntos_visitante');
            }

            if (Schema::hasColumn('partidos', 'puntos_local')) {
                $table->dropColumn('puntos_local');
            }
        });
    }
};
