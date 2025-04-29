<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\GoogleCalendarLog;
use Carbon\Carbon;
use Exception;

class GoogleCalendarServices
{
    private function refreshGoogleToken($user)
    {
        if (!$user->google_refresh_token) {
            throw new Exception('No existe refresh token para este usuario.');
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->google_refresh_token,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
        ]);

        if ($response->failed()) {
            throw new Exception('Error al refrescar el token de Google: ' . $response->body());
        }

        $data = $response->json();

        $user->google_token = $data['access_token'];
        $user->save();

        return $user->google_token;
    }

    private function getValidToken($user)
    {
        if (!$user->google_token) {
            if ($user->isAdmin()) {
                return null;
            }
            throw new Exception('El usuario no tiene un token de Google válido.');
        }

        return $user->google_token;
    }

    private function logAction($userId, $action, $googleEventId = null, $status = 'success', $details = null)
    {
        GoogleCalendarLog::create([
            'user_id' => $userId,
            'action' => $action,
            'google_event_id' => $googleEventId,
            'status' => $status,
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }

    public function createEvent($user, $titulo, $descripcion, $fechaInicio, $fechaFin, $colorId = 8)
    {
        if ($user->isAdmin() && !$user->google_token) {
            return null; // Ignorar sin error
        }

        try {
            $token = $this->getValidToken($user);

            $response = Http::withToken($token)
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

            if ($response->unauthorized()) {
                $token = $this->refreshGoogleToken($user);
                $response = Http::withToken($token)->post('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
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
            }

            if ($response->failed()) {
                throw new Exception('Error al crear evento: ' . $response->body());
            }

            $data = $response->json();
            $this->logAction($user->id, 'create', $data['id'] ?? null, 'success', $data);

            return $data;
        } catch (Exception $e) {
            $this->logAction($user->id, 'create', null, 'error', $e->getMessage());
            throw new Exception('Error al crear evento: ' . $e->getMessage());
        }
    }

    public function updateEvent($user, $eventId, $titulo, $descripcion, $fechaInicio, $fechaFin, $colorId = 8)
    {
        if ($user->isAdmin() && !$user->google_token) {
            return null;
        }

        try {
            $token = $this->getValidToken($user);

            $response = Http::withToken($token)
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

            if ($response->unauthorized()) {
                $token = $this->refreshGoogleToken($user);
                $response = Http::withToken($token)
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
            }

            if ($response->failed()) {
                throw new Exception('Error al actualizar evento: ' . $response->body());
            }

            $this->logAction($user->id, 'update', $eventId, 'success', $response->json());

            return $response->json();
        } catch (Exception $e) {
            $this->logAction($user->id, 'update', $eventId, 'error', $e->getMessage());
            throw new Exception('Error al actualizar evento: ' . $e->getMessage());
        }
    }

    public function deleteEvent($user, $eventId)
    {
        if ($user->isAdmin() && !$user->google_token) {
            return true;
        }

        try {
            $token = $this->getValidToken($user);

            $response = Http::withToken($token)
                ->delete("https://www.googleapis.com/calendar/v3/calendars/primary/events/{$eventId}");

            if ($response->unauthorized()) {
                $token = $this->refreshGoogleToken($user);
                $response = Http::withToken($token)
                    ->delete("https://www.googleapis.com/calendar/v3/calendars/primary/events/{$eventId}");
            }

            if ($response->failed()) {
                throw new Exception('Error al eliminar evento: ' . $response->body());
            }

            $this->logAction($user->id, 'delete', $eventId, 'success', '{}');

            return true;
        } catch (Exception $e) {
            $this->logAction($user->id, 'delete', $eventId, 'error', $e->getMessage());
            throw new Exception('Error al eliminar evento: ' . $e->getMessage());
        }
    }
}
