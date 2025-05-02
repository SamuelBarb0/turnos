<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->string('timezone')->default('America/Bogota')->after('fecha_de_la_cita');
        });
    }
    
    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }    
};
