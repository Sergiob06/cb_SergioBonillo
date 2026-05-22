<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('partidos', 'category_id')) {
            Schema::table('partidos', function (Blueprint $table) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('equipo_visitante_id')
                    ->constrained('categories')
                    ->nullOnDelete();
            });
        }

        DB::table('partidos')
            ->join('equipos', 'partidos.equipo_local_id', '=', 'equipos.id')
            ->whereNull('partidos.category_id')
            ->whereNotNull('equipos.category_id')
            ->update(['partidos.category_id' => DB::raw('equipos.category_id')]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('partidos', 'category_id')) {
            Schema::table('partidos', function (Blueprint $table) {
                $table->dropConstrainedForeignId('category_id');
            });
        }
    }
};
