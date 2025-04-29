<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\GoogleCalendarLog;
use Carbon\Carbon;

class GoogleCalendarServices
{
    public function createEvent($user, $titulo, $descripcion, $fechaInicio, $fechaFin, $colorId = 8)
    {
        if (!$user->google_token) {
            $this->logAction($user->id, 'error_no_token', null, 'Usuario sin token de Google válido');
            throw new \Exception('El usuario no tiene un token de Google válido.');
        }

        $response = Http::withToken($user->google_token)
            ->post('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
                'summary' => $titulo,
                'description' => $descripcion,
                'start' => [
                    'dateTime' => $fechaInicio->toRfc3339String(),
                    'timeZone' => 'America/Bogota',
                ],
                'end' => [
                    'dateTime' => $fechaFin->toRfc3339String(),
                    'timeZone' => 'America/Bogota',
                ],
                'colorId' => $colorId,
            ]);

        if ($response->failed()) {
            $this->logAction($user->id, 'error_creating_event', null, $response->body());
            throw new \Exception('Error al crear evento en Google Calendar: ' . $response->body());
        }

        // Si se creó exitosamente, guardamos el log
        $eventData = $response->json();
        $this->logAction($user->id, 'create_event', $eventData['id'] ?? null, $eventData);

        return $eventData;
    }

    public function updateEvent($user, $eventId, $titulo, $descripcion, $fechaInicio, $fechaFin, $colorId = 8)
    {
        if (!$user->google_token) {
            $this->logAction($user->id, 'error_no_token_update', $eventId, 'Usuario sin token válido para actualizar evento');
            throw new \Exception('El usuario no tiene un token de Google válido.');
        }

        $response = Http::withToken($user->google_token)
            ->patch("https://www.googleapis.com/calendar/v3/calendars/primary/events/{$eventId}", [
                'summary' => $titulo,
                'description' => $descripcion,
                'start' => [
                    'dateTime' => $fechaInicio->toRfc3339String(),
                    'timeZone' => 'America/Bogota',
                ],
                'end' => [
                    'dateTime' => $fechaFin->toRfc3339String(),
                    'timeZone' => 'America/Bogota',
                ],
                'colorId' => $colorId,
            ]);

        if ($response->failed()) {
            $this->logAction($user->id, 'error_updating_event', $eventId, $response->body());
            throw new \Exception('Error al actualizar evento: ' . $response->body());
        }

        $this->logAction($user->id, 'update_event', $eventId, $response->json());

        return $response->json();
    }

    public function deleteEvent($user, $eventId)
    {
        if (!$user->google_token) {
            $this->logAction($user->id, 'error_no_token_delete', $eventId, 'Usuario sin token válido para eliminar evento');
            throw new \Exception('El usuario no tiene un token de Google válido.');
        }

        $response = Http::withToken($user->google_token)
            ->delete("https://www.googleapis.com/calendar/v3/calendars/primary/events/{$eventId}");

        if ($response->failed()) {
            $this->logAction($user->id, 'error_deleting_event', $eventId, $response->body());
            throw new \Exception('Error al eliminar evento: ' . $response->body());
        }

        $this->logAction($user->id, 'delete_event', $eventId, 'Evento eliminado exitosamente');
    }



    private function logAction($userId, $action, $googleEventId = null, $details = null)
    {
        GoogleCalendarLog::create([
            'user_id' => $userId,
            'action' => $action,
            'google_event_id' => $googleEventId,
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }
}
