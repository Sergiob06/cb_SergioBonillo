<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('categoria')->constrained('categories')->nullOnDelete();
            $table->text('descripcion')->nullable()->after('imagen_club');
        });

        $categorias = DB::table('equipos')
            ->select('categoria')
            ->whereNotNull('categoria')
            ->where('categoria', '!=', '')
            ->distinct()
            ->pluck('categoria');

        if ($categorias->isEmpty()) {
            $categorias = collect(['Senior', 'Junior', 'Cadete', 'Infantil']);
        }

        foreach ($categorias as $nombreCategoria) {
            $categoryId = DB::table('categories')->where('name', $nombreCategoria)->value('id');

            if (!$categoryId) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $nombreCategoria,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('equipos')
                ->where('categoria', $nombreCategoria)
                ->update(['category_id' => $categoryId]);
        }
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn('descripcion');
        });
    }
};
