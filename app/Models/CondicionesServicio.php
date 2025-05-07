<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionesServicio extends Model
{
    use HasFactory;

    protected $table = 'condiciones_servicio';

    protected $fillable = [
        'contenido',
    ];
}