<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleCalendarLogsTable extends Migration
{
    public function up()
    {
        Schema::create('google_calendar_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // crear, actualizar, eliminar, error, renovar_token, etc
            $table->string('google_event_id')->nullable();
            $table->text('details')->nullable(); // Aquí puedes guardar respuesta completa o errores
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_calendar_logs');
    }
}
