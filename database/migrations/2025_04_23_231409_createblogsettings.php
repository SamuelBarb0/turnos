<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Blog de Agendux');
            $table->text('description')->nullable();
            $table->string('background_color')->default('#f9fafb');
            $table->timestamps();
        });
        
        // Insertar configuración predeterminada
        DB::table('blog_settings')->insert([
            'title' => 'Blog de Agendux',
            'description' => 'Amplía tus conocimientos sobre gestión de citas y organización personal con los mejores consejos de Agendux.',
            'background_color' => '#f9fafb',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_settings');
    }
}