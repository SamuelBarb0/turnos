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
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_solicitud');
            $table->dateTime('fecha_de_la_cita');
            $table->dateTime('fecha_actualizacion_cita')->nullable();
            $table->string('google_event_id')->nullable();
            $table->json('recordatorios')->nullable(); // Para almacenar la configuración de recordatorios
            $table->string('estado')->default('pendiente'); // pendiente, confirmada, cancelada, etc.
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};