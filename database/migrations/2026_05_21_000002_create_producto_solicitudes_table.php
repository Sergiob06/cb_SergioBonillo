<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono')->nullable();
            $table->text('mensaje')->nullable();
            $table->string('estado')->default('pendiente')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_solicitudes');
    }
};
