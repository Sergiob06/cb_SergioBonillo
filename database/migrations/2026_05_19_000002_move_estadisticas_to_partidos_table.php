<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->foreignKeyExists('estadisticas', 'estadisticas_equipo_id_foreign')) {
            Schema::table('estadisticas', function (Blueprint $table) {
                $table->dropForeign(['equipo_id']);
            });
        }

        if ($this->indexExists('estadisticas', 'estadisticas_equipo_id_temporada_unique')) {
            Schema::table('estadisticas', function (Blueprint $table) {
                $table->dropUnique('estadisticas_equipo_id_temporada_unique');
            });
        }

        Schema::table('estadisticas', function (Blueprint $table) {
            if (!Schema::hasColumn('estadisticas', 'partido_id')) {
                $table->foreignId('partido_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('partidos')
                    ->cascadeOnDelete();
            }
        });

        Schema::table('estadisticas', function (Blueprint $table) {
            if (Schema::hasColumn('estadisticas', 'equipo_id')) {
                $table->unsignedBigInteger('equipo_id')->nullable()->change();
            }

            if (Schema::hasColumn('estadisticas', 'temporada')) {
                $table->string('temporada')->nullable()->change();
            }
        });

        if (!$this->indexExists('estadisticas', 'estadisticas_partido_id_unique')) {
            Schema::table('estadisticas', function (Blueprint $table) {
                $table->unique('partido_id');
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('estadisticas', 'estadisticas_partido_id_unique')) {
            Schema::table('estadisticas', function (Blueprint $table) {
                $table->dropUnique('estadisticas_partido_id_unique');
            });
        }

        Schema::table('estadisticas', function (Blueprint $table) {
            if (Schema::hasColumn('estadisticas', 'partido_id')) {
                $table->dropConstrainedForeignId('partido_id');
            }
        });

        Schema::table('estadisticas', function (Blueprint $table) {
            if (Schema::hasColumn('estadisticas', 'equipo_id')) {
                $table->unsignedBigInteger('equipo_id')->nullable(false)->change();
            }

            if (Schema::hasColumn('estadisticas', 'temporada')) {
                $table->string('temporada')->nullable(false)->change();
            }
        });

        if (!$this->indexExists('estadisticas', 'estadisticas_equipo_id_temporada_unique')) {
            Schema::table('estadisticas', function (Blueprint $table) {
                $table->unique(['equipo_id', 'temporada']);
            });
        }

        if (!$this->foreignKeyExists('estadisticas', 'estadisticas_equipo_id_foreign')) {
            Schema::table('estadisticas', function (Blueprint $table) {
                $table->foreign('equipo_id')->references('id')->on('equipos')->cascadeOnDelete();
            });
        }
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        return !empty(DB::select(
            'select CONSTRAINT_NAME from information_schema.KEY_COLUMN_USAGE where TABLE_SCHEMA = DATABASE() and TABLE_NAME = ? and CONSTRAINT_NAME = ?',
            [$table, $constraint]
        ));
    }

    private function indexExists(string $table, string $index): bool
    {
        return !empty(DB::select(
            'select INDEX_NAME from information_schema.STATISTICS where TABLE_SCHEMA = DATABASE() and TABLE_NAME = ? and INDEX_NAME = ?',
            [$table, $index]
        ));
    }
};
