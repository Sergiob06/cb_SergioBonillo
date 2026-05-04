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
        Schema::table('partidos', function (Blueprint $table) {
            $table->foreignId('equipo_local_id')->nullable()->after('equipo_id')->constrained('equipos')->nullOnDelete();
            $table->foreignId('equipo_visitante_id')->nullable()->after('equipo_local_id')->constrained('equipos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('equipo_local_id');
            $table->dropConstrainedForeignId('equipo_visitante_id');
        });
    }
};
