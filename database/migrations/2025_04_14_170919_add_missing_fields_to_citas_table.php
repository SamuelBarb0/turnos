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
        Schema::table('citas', function (Blueprint $table) {
            $table->string('color_estado')->nullable()->after('estado');
            $table->boolean('mensaje_enviado')->default(false)->after('color_estado');
            $table->text('respuesta_cliente')->nullable()->after('mensaje_enviado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['color_estado', 'mensaje_enviado', 'respuesta_cliente']);
        });
    }
};