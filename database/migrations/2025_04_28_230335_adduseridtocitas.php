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
            // Agregar columna user_id como unsignedBigInteger y nullable (por si algunas citas viejas no tienen usuario asociado)
            $table->unsignedBigInteger('user_id')->nullable()->after('id_cita');

            // Definir la llave foránea
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // Si el usuario se elimina, el user_id de la cita quedará como NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Primero quitar la llave foránea
            $table->dropForeign(['user_id']);

            // Luego eliminar la columna
            $table->dropColumn('user_id');
        });
    }
};
