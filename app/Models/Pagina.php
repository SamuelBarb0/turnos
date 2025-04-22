<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title'];

    public function secciones()
    {
        return $this->hasMany(PaginaSeccion::class, 'pagina_id')->orderBy('orden');
    }
}
