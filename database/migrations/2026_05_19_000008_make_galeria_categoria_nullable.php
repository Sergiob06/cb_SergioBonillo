<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('galerias', 'categoria')) {
            return;
        }

        DB::table('galerias')
            ->where(function ($query) {
                $query->whereNull('titulo')->orWhere('titulo', '');
            })
            ->whereNotNull('categoria')
            ->update(['titulo' => DB::raw('categoria')]);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `galerias` MODIFY `categoria` VARCHAR(255) NULL');
            return;
        }

        Schema::table('galerias', function ($table) {
            $table->string('categoria')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('galerias', 'categoria')) {
            return;
        }

        DB::table('galerias')
            ->whereNull('categoria')
            ->update(['categoria' => DB::raw("COALESCE(NULLIF(titulo, ''), 'Galeria')")]);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `galerias` MODIFY `categoria` VARCHAR(255) NOT NULL");
            return;
        }

        Schema::table('galerias', function ($table) {
            $table->string('categoria')->nullable(false)->change();
        });
    }
};
