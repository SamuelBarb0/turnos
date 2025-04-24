<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'titulo',
        'slug',
        'resumen',
        'contenido',
        'imagen',
        'categoria',
        'etiquetas',
        'autor',
        'tiempo_lectura',
        'estado',
        'fecha_publicacion',
        'user_id',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha_publicacion' => 'datetime',
    ];

    /**
     * Obtiene el usuario que creó el artículo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar artículos publicados.
     */
    public function scopePublicados($query)
    {
        return $query->where('estado', 'publicado')
                    ->where('fecha_publicacion', '<=', now());
    }

    /**
     * Convierte las etiquetas a un array.
     */
    public function getEtiquetasArrayAttribute()
    {
        if (empty($this->etiquetas)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->etiquetas));
    }
}