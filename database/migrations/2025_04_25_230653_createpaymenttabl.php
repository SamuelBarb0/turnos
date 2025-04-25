<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('preference_id');
            $table->string('payment_id')->nullable();
            $table->string('external_reference');
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('status')->default('pending');
            $table->json('payment_details')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Campos para relación polimórfica (puede ser una Cita u otro modelo)
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable();
            
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('preference_id');
            $table->index('payment_id');
            $table->index('external_reference');
            $table->index('status');
            
            // Si tienes una tabla de usuarios, añade esta relación
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};