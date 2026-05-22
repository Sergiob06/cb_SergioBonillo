<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('clasificaciones');
    }

    public function down(): void
    {
        // La sección de clasificación se ha retirado del proyecto.
    }
};
