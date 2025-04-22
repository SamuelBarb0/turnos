<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenidoSeccion extends Model
{
    use HasFactory;

    protected $table = 'contenido_secciones';
    protected $fillable = ['id_pagina_seccion', 'etiqueta', 'contenido', 'orden'];

    public function seccion()
    {
        return $this->belongsTo(PaginaSeccion::class, 'id_pagina_seccion');
    }
}