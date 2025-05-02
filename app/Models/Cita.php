<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'citas';

    /**
     * La clave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_cita';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_solicitud',
        'fecha_de_la_cita',
        'fecha_actualizacion_cita',
        'google_event_id',
        'prefijo', // <<<<< NUEVO
        'recordatorios',
        'estado',
        'color_estado',
        'mensaje_enviado',
        'respuesta_cliente',
        'user_id',
        'telefono', // <<< recuerda que también tienes teléfono
        'timezone', // <<< recuerda que también tienes teléfono
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_de_la_cita' => 'datetime',
        'fecha_actualizacion_cita' => 'datetime',
        'recordatorios' => 'array',
        'mensaje_enviado' => 'boolean',
    ];

    /**
     * Get the color based on the status
     *
     * @return string
     */
    public function getColorEstado()
    {
        switch ($this->estado) {
            case 'mensaje_enviado':
                return '#FFD700'; // Amarillo para mensaje enviado
            case 'confirmada':
                return '#28a745'; // Verde para confirmada
            case 'cancelada':
                return '#dc3545'; // Rojo para cancelada
            case 'pendiente':
            default:
                return '#6c757d'; // Gris para pendiente
        }
    }

    /**
     * Update the status and color
     *
     * @param string $estado
     * @return void
     */
    public function actualizarEstado($estado)
    {
        $this->estado = $estado;
        $this->color_estado = $this->getColorEstado();
        $this->save();
    }


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getTelefonoCompletoAttribute()
    {
        if (!$this->telefono) {
            return null;
        }

        $prefijo = $this->prefijo ?? '57';

        return $prefijo . $this->telefono;
    }

    public function getFechaCitaFormateadaAttribute()
    {
        return $this->fecha_de_la_cita
            ? $this->fecha_de_la_cita->setTimezone($this->timezone)->format('d/m/Y H:i')
            : null;
    }
}
