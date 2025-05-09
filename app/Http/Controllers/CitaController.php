<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Services\GoogleCalendarServices; // arriba
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\TwilioService; // Importar el nuevo servicio

class CitaController extends Controller
{
    /**
     * Mostrar lista de todas las citas.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Admin ve todas
            $citas = Cita::orderBy('fecha_de_la_cita', 'asc')->get();
            $esMisCitas = false;
        } else {
            // Usuario normal solo sus citas
            $citas = Cita::where('user_id', $user->id)
                ->orderBy('fecha_de_la_cita', 'asc')
                ->get();
            $esMisCitas = true;
        }

        return view('citas.index', compact('citas', 'esMisCitas'));
    }

    private function authorizeAccess(Cita $cita)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $cita->user_id !== $user->id) {
            abort(403, 'No tienes permiso para acceder a esta cita.');
        }
    }


    /**
     * Mostrar formulario para crear nueva cita.
     */
    public function create()
    {
        return view('citas.create');
    }



    public function store(Request $request, GoogleCalendarServices $calendarService)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_de_la_cita' => 'required|date',
            'timezone' => 'required|string', // nuevo
            'recordatorios' => 'nullable|array',
        ]);
    
        $timezone = $validated['timezone'];
        $fechaSolicitud = Carbon::now();
        
        // Crear fecha en la zona horaria seleccionada por el usuario
        $fechaCitaLocal = Carbon::parse($validated['fecha_de_la_cita'], $timezone);
        $fechaFinLocal = $fechaCitaLocal->copy()->addHour();
    
        // Guardar en UTC para la base de datos (recomendado)
        $fechaCitaUTC = $fechaCitaLocal->copy()->setTimezone('UTC');
    
        $estado = 'pendiente';
        $colorEstado = '#6c757d'; // gris para pendiente
    
        $user = auth()->user();
    
        // Crear evento en Google Calendar en la zona horaria seleccionada
        $evento = $calendarService->createEvent(
            $user,
            $validated['titulo'],
            $validated['descripcion'] ?? '',
            $fechaCitaLocal,
            $fechaFinLocal,
            8, // color gris
            $timezone // <-- importante, pasa la zona horaria correcta
        );
    
        // Guardar cita en BD (en UTC)
        $cita = Cita::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'fecha_solicitud' => $fechaSolicitud,
            'fecha_de_la_cita' => $fechaCitaUTC,
            'timezone' => $timezone,
            'google_event_id' => $evento['id'] ?? null,
            'recordatorios' => $validated['recordatorios'] ?? null,
            'estado' => $estado,
            'color_estado' => $colorEstado,
            'mensaje_enviado' => false,
            'respuesta_cliente' => null,
            'user_id' => $user->id,
        ]);
    
        // Notificar al usuario
        $cita->user->notify(new \App\Notifications\CitaCreadaNotification($cita));
    
        return redirect()->route('citas.preparar.mensaje', $cita->id_cita)
            ->with('success', 'Cita creada. Ahora prepara el mensaje para el cliente.');
    }
    



    /**
     * Mostrar formulario para preparar mensaje de WhatsApp.
     */
    public function prepararMensaje(Cita $cita)
    {
        // Plantilla predeterminada del mensaje
        $mensajePredeterminado = "Hola, te confirmo tu cita para el {$cita->fecha_de_la_cita->format('d/m/Y')} a las {$cita->fecha_de_la_cita->format('H:i')}. Por favor, responde 'Confirmar' para aceptar o 'Cancelar' si necesitas reprogramar. Gracias.";

        return view('citas.mensaje', compact('cita', 'mensajePredeterminado'));
    }

    public function enviarMensaje(Request $request, Cita $cita, \App\Services\TwilioService $twilioService, \App\Services\GoogleCalendarServices $calendarService)
{
    // Validar entrada incluyendo prefijo
    $validated = $request->validate([
        'prefijo'  => 'required|string',
        'telefono' => 'required|string',
        'mensaje'  => 'required|string',
    ]);

    // Limpiar prefijo y teléfono (dejar solo números)
    $prefijo = preg_replace('/[^0-9]/', '', $validated['prefijo']);
    $telefono = preg_replace('/[^0-9]/', '', $validated['telefono']);

    // Combinar para obtener el número completo para WhatsApp/Twilio
    $numeroCompleto = $prefijo . $telefono;

    // Guardar prefijo y teléfono en la cita
    $cita->prefijo = $prefijo;
    $cita->telefono = $telefono;

    // Definir los botones interactivos
    $buttons = [
        [
            'id'   => 'confirmar_button',
            'text' => 'Confirmar'
        ],
        [
            'id'   => 'cancelar_button',
            'text' => 'Cancelar'
        ]
    ];

    // Enviar el mensaje utilizando el servicio de Twilio
    $resultado = $twilioService->enviarMensajeConBotones(
        $numeroCompleto,
        $validated['mensaje'],
        $buttons
    );

    if ($resultado['success']) {
        // Actualizar estado de la cita
        $cita->estado = 'mensaje_enviado';
        $cita->color_estado = $cita->getColorEstado();
        $cita->mensaje_enviado = true;
        $cita->save();

        // Actualizar Google Calendar color amarillo usando GoogleCalendarServices
        try {
            $calendarService->updateEvent(
                auth()->user(),
                $cita->google_event_id,
                $cita->titulo,
                $cita->descripcion ?? '',
                Carbon::parse($cita->fecha_de_la_cita),
                Carbon::parse($cita->fecha_de_la_cita)->copy()->addHour(),
                5 // Color amarillo en Google Calendar
            );
        } catch (\Exception $e) {
            \Log::error('Error al actualizar evento en Google Calendar: ' . $e->getMessage());
        }

        return redirect()->route('citas.index')
            ->with('success', 'Mensaje enviado. Esperando respuesta del cliente.');
    } else {
        return redirect()->back()
            ->with('error', 'Error al enviar el mensaje: ' . ($resultado['error'] ?? 'Error desconocido'))
            ->withInput();
    }
}

    
    


    public function procesarRespuesta(Request $request)
{
    \Log::info('Webhook recibido: ' . json_encode($request->all()));

    $data = $request->all();
    $telefonoCompleto = null;
    $telefonoSinPrefijo = null;
    $respuesta = null;

    // Extraer teléfono y respuesta
    if (isset($data['From'])) {
        // Formato WhatsApp/Twilio estándar
        $telefonoCompleto = str_replace(['whatsapp:', '+'], '', $data['From']);
        $telefonoSinPrefijo = substr($telefonoCompleto, -10); // Últimos 10 dígitos

        if (isset($data['Body'])) {
            $respuestaTexto = strtolower(trim($data['Body']));
            \Log::info("Respuesta recibida: {$respuestaTexto}");

            if ($respuestaTexto == 'confirmar') {
                $respuesta = 'confirmar_button';
            } elseif ($respuestaTexto == 'cancelar') {
                $respuesta = 'cancelar_button';
            } else {
                \Log::warning('Respuesta no reconocida: ' . $respuestaTexto);
                return response()->json(['status' => 'error', 'message' => 'Respuesta no reconocida']);
            }
        }
    } elseif (isset($data['body']['message']['interactive']['button_reply']) && isset($data['body']['user']['phone'])) {
        $telefonoCompleto = preg_replace('/[^0-9]/', '', $data['body']['user']['phone']);
        $telefonoSinPrefijo = substr($telefonoCompleto, -10);

        $respuesta = $data['body']['message']['interactive']['button_reply']['id'];
    }

    if (!$telefonoSinPrefijo || !$respuesta) {
        \Log::warning('Formato de mensaje no válido o información faltante');
        return response()->json(['status' => 'error', 'message' => 'Formato de mensaje no válido']);
    }

    // Buscar cita por prefijo + teléfono
    $prefijo = substr($telefonoCompleto, 0, strlen($telefonoCompleto) - strlen($telefonoSinPrefijo));

    $cita = Cita::where('telefono', $telefonoSinPrefijo)
        ->where(function ($query) use ($prefijo) {
            $query->where('prefijo', $prefijo)->orWhereNull('prefijo');
        })
        ->where('mensaje_enviado', true)
        ->whereIn('estado', ['mensaje_enviado', 'pendiente'])
        ->orderBy('id_cita', 'desc')
        ->first();

    if (!$cita) {
        \Log::warning('Cita no encontrada para el teléfono: ' . $telefonoCompleto);
        return response()->json(['status' => 'error', 'message' => 'Cita no encontrada']);
    }

    \Log::info("Cita encontrada: ID {$cita->id_cita}");

    if ($respuesta === 'confirmar_button') {
        \Log::info("Confirmando cita: {$cita->id_cita}");
        $cita->estado = 'confirmada';
        $cita->color_estado = $cita->getColorEstado();

        try {
            $event = Event::find($cita->google_event_id);
            if ($event) {
                $event->colorId = 10; // Verde
                $event->save();
            }
        } catch (\Exception $e) {
            \Log::error('Error al actualizar evento en Google Calendar: ' . $e->getMessage());
        }
    } elseif ($respuesta === 'cancelar_button') {
        \Log::info("Cancelando cita: {$cita->id_cita}");
        $cita->estado = 'cancelada';
        $cita->color_estado = $cita->getColorEstado();

        try {
            $event = Event::find($cita->google_event_id);
            if ($event) {
                $event->colorId = 11; // Rojo
                $event->save();
            }
        } catch (\Exception $e) {
            \Log::error('Error al actualizar evento en Google Calendar: ' . $e->getMessage());
        }
    }

    $cita->respuesta_cliente = $respuesta;
    $cita->save();

    \Log::info("Cita actualizada. Nuevo estado: {$cita->estado}");

    return response()->json(['status' => 'success', 'message' => 'Respuesta procesada']);
}

    
    public function show(Cita $cita)
    {
        $this->authorizeAccess($cita);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $this->authorizeAccess($cita);
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, Cita $cita, GoogleCalendarServices $calendarService)
{
    $this->authorizeAccess($cita);

    $validated = $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'fecha_de_la_cita' => 'required|date',
        'timezone' => 'required|string',
        'recordatorios' => 'nullable|array',
        'estado' => 'required|string|in:pendiente,mensaje_enviado,confirmada,cancelada',
    ]);

    $timezone = $validated['timezone'];
    
    // Crear fecha en la zona horaria seleccionada
    $fechaCitaLocal = Carbon::parse($validated['fecha_de_la_cita'], $timezone);
    $fechaFinLocal = $fechaCitaLocal->copy()->addHour();

    // Guardar en UTC para base de datos
    $fechaCitaUTC = $fechaCitaLocal->copy()->setTimezone('UTC');

    $nuevoEstado = $validated['estado'];

    // Color para estado
    $colorId = match($nuevoEstado) {
        'mensaje_enviado' => 5, // Amarillo
        'confirmada' => 10,     // Verde
        'cancelada' => 11,      // Rojo
        default => 8,           // Gris
    };

    $user = auth()->user();

    // Actualizar en Google Calendar
    if ($cita->google_event_id) {
        $calendarService->updateEvent(
            $user,
            $cita->google_event_id,
            $validated['titulo'],
            $validated['descripcion'] ?? '',
            $fechaCitaLocal,
            $fechaFinLocal,
            $colorId,
            $timezone // <-- importante actualizar también la zona horaria
        );
    }

    // Actualizar en base de datos
    $cita->update([
        'titulo' => $validated['titulo'],
        'descripcion' => $validated['descripcion'] ?? $cita->descripcion,
        'fecha_de_la_cita' => $fechaCitaUTC,
        'timezone' => $timezone,
        'fecha_actualizacion_cita' => Carbon::now(),
        'recordatorios' => $validated['recordatorios'] ?? $cita->recordatorios,
        'estado' => $nuevoEstado,
        'color_estado' => $cita->getColorEstado(),
    ]);

    return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
}


    public function destroy(Cita $cita, GoogleCalendarServices $calendarService)
    {
        $this->authorizeAccess($cita);

        $user = auth()->user();

        if ($cita->google_event_id) {
            $calendarService->deleteEvent($user, $cita->google_event_id);
        }

        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }


    /**
     * Sincronizar eventos desde Google Calendar.
     */
    public function sincronizarCalendario()
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonth();

        // Obtenemos eventos desde Google Calendar
        $events = Event::get($startDate, $endDate);

        foreach ($events as $event) {
            // Comprobar si ya existe una cita con este ID de evento
            $cita = Cita::where('google_event_id', $event->id)->first();

            if ($cita) {
                // Actualizar cita existente
                $cita->update([
                    'titulo' => $event->name,
                    'descripcion' => $event->description,
                    'fecha_de_la_cita' => $event->startDateTime,
                    'fecha_actualizacion_cita' => Carbon::now(),
                ]);
            } else {
                // Crear nueva cita
                Cita::create([
                    'titulo' => $event->name,
                    'descripcion' => $event->description,
                    'fecha_solicitud' => Carbon::now(),
                    'fecha_de_la_cita' => $event->startDateTime,
                    'google_event_id' => $event->id,
                    'estado' => 'pendiente',
                    'color_estado' => '#6c757d'
                ]);
            }
        }

        return redirect()->route('citas.index')
            ->with('success', 'Sincronización con Google Calendar completada');
    }
}
