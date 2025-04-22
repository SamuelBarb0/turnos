<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaginaSeccion extends Model
{
    use HasFactory;

    protected $table = 'paginas_seccion';
    protected $fillable = ['pagina_id', 'seccion', 'ruta_image', 'orden'];

    public function pagina()
    {
        return $this->belongsTo(Pagina::class, 'pagina_id');
    }

    public function contenidos()
    {
        return $this->hasMany(ContenidoSeccion::class, 'id_pagina_seccion')->orderBy('orden');
    }
}