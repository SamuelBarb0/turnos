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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('resumen')->nullable();
            $table->longText('contenido');
            $table->string('imagen')->nullable();
            $table->string('categoria');
            $table->string('etiquetas')->nullable();
            $table->string('autor');
            $table->integer('tiempo_lectura')->default(3);
            $table->enum('estado', ['publicado', 'borrador'])->default('borrador');
            $table->timestamp('fecha_publicacion');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};