<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mensaje;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's message templates (incluso si está vacío)
        $mensajes = Mensaje::where('user_id', $user->id)
            ->where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get upcoming appointments (filtrado por usuario logueado)
        $citas = Cita::where('user_id', $user->id)
            ->where('estado', '!=', 'cancelada')
            ->where('fecha_de_la_cita', '>=', Carbon::today())
            ->orderBy('fecha_de_la_cita', 'asc')
            ->get();


        // Get calendar data (appointments grouped by date)
        $calendarData = $this->getCalendarData();

        // Get recent payments (incluso si está vacío)
        $payments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Inicializar objetos vacíos para evitar errores en la vista
        if (!$mensajes) $mensajes = collect([]);
        if (!$citas) $citas = collect([]);
        if (!$payments) $payments = collect([]);
        if (!$calendarData) $calendarData = [];

        return view('dashboard.index', compact('user', 'mensajes', 'citas', 'calendarData', 'payments'));
    }

    /**
     * Update user profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Store a new message template
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMensaje(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            'tipo' => 'required|string',
        ]);

        Mensaje::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'tipo' => $validated['tipo'],
            'is_active' => 1,
        ]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Mensaje creado correctamente');
    }

    /**
     * Update an existing message template
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMensaje(Request $request, $id)
    {
        $mensaje = Mensaje::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            'tipo' => 'required|string',
        ]);

        $mensaje->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'tipo' => $validated['tipo'],
        ]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Mensaje actualizado correctamente');
    }

    /**
     * Delete a message template
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteMensaje($id)
    {
        $mensaje = Mensaje::where('user_id', Auth::id())
            ->findOrFail($id);

        // Instead of deleting, we set is_active to 0
        $mensaje->update(['is_active' => 0]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Mensaje eliminado correctamente');
    }

    /**
     * Get a template for editing (AJAX)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editMensaje($id)
    {
        $mensaje = Mensaje::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($mensaje);
    }

    /**
     * Get appointment details (AJAX)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCita($id)
    {
        try {
            $cita = Cita::findOrFail($id);

            // Format dates for display
            $cita->fecha_formateada = Carbon::parse($cita->fecha_de_la_cita)->format('d/m/Y');
            $cita->hora_formateada = Carbon::parse($cita->fecha_de_la_cita)->format('H:i');

            return response()->json($cita);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'No se encontró la cita solicitada'
            ], 404);
        }
    }

    /**
     * Confirm an appointment
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmCita($id)
    {
        try {
            $cita = Cita::findOrFail($id);

            $cita->update([
                'estado' => 'confirmada',
                'color_estado' => 'green',
                'fecha_actualizacion_cita' => Carbon::now(),
            ]);

            // Here you could also send a confirmation message to the client

            return response()->json([
                'success' => true,
                'message' => 'Cita confirmada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an appointment
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelCita($id)
    {
        try {
            $cita = Cita::findOrFail($id);

            $cita->update([
                'estado' => 'cancelada',
                'color_estado' => 'red',
                'fecha_actualizacion_cita' => Carbon::now(),
            ]);

            // Here you could also send a cancellation message to the client

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments for a specific date (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitasByDate(Request $request)
    {
        try {
            $validated = $request->validate([
                'year' => 'required|integer',
                'month' => 'required|integer',
                'day' => 'required|integer',
            ]);

            $date = Carbon::createFromDate(
                $validated['year'],
                $validated['month'],
                $validated['day']
            )->format('Y-m-d');

            $citas = Cita::where('user_id', Auth::id())
            ->whereDate('fecha_de_la_cita', $date)
            ->orderBy('fecha_de_la_cita', 'asc')
            ->get();
        
            // Format the appointments for display
            $formattedCitas = $citas->map(function ($cita) {
                return [
                    'id_cita' => $cita->id_cita,
                    'titulo' => $cita->titulo,
                    'hora' => Carbon::parse($cita->fecha_de_la_cita)->format('H:i'),
                    'estado' => $cita->estado,
                    'color_estado' => $cita->color_estado,
                    'descripcion' => $cita->descripcion,
                    'telefono' => $cita->telefono,
                ];
            });

            return response()->json([
                'date' => Carbon::parse($date)->format('d/m/Y'),
                'citas' => $formattedCitas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'date' => $request->has('day') ? "{$request->day}/{$request->month}/{$request->year}" : 'Fecha desconocida',
                'message' => 'Error al cargar las citas: ' . $e->getMessage(),
                'citas' => []
            ], 500);
        }
    }

    /**
     * Send reminder message to client
     *
     * @param  int  $citaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReminderMessage($citaId)
    {
        try {
            $cita = Cita::findOrFail($citaId);

            if (!$cita->telefono) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cita no tiene un número de teléfono asociado'
                ], 400);
            }

            // Mark message as sent
            $cita->update([
                'mensaje_enviado' => 1,
            ]);

            // Here you would implement your actual message sending logic
            // For example, using WhatsApp API or SMS service

            return response()->json([
                'success' => true,
                'message' => 'Recordatorio enviado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el recordatorio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get calendar data (appointments grouped by date)
     *
     * @return array
     */
    private function getCalendarData()
    {
        try {
            // Get appointments for the current month
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $citas = Cita::where('user_id', Auth::id())
            ->whereBetween('fecha_de_la_cita', [$startOfMonth, $endOfMonth])
            ->get();
        

            // Group appointments by date
            $calendarData = [];

            foreach ($citas as $cita) {
                $dateKey = Carbon::parse($cita->fecha_de_la_cita)->format('Y-m-d');

                if (!isset($calendarData[$dateKey])) {
                    $calendarData[$dateKey] = [
                        'count' => 0,
                        'citas' => []
                    ];
                }

                $calendarData[$dateKey]['count']++;
                $calendarData[$dateKey]['citas'][] = [
                    'id_cita' => $cita->id_cita,
                    'titulo' => $cita->titulo,
                    'hora' => Carbon::parse($cita->fecha_de_la_cita)->format('H:i'),
                    'estado' => $cita->estado,
                ];
            }

            return $calendarData;
        } catch (\Exception $e) {
            // En caso de error, devolver un arreglo vacío para evitar errores en la vista
            return [];
        }
    }
}
