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
        Schema::create('contenido_secciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pagina_seccion')->constrained('paginas_seccion')->onDelete('cascade');
            $table->string('etiqueta'); // h1, h2, p, button, etc.
            $table->text('contenido');
            $table->integer('orden')->default(0); // Para ordenar los elementos dentro de la misma sección
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenido_secciones');
    }
};