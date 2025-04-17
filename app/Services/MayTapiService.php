<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MayTapiService
{
    protected $baseUrl;
    protected $apiToken;
    protected $productId;
    protected $phoneId;

    public function __construct()
    {
        $this->productId = env('MAYTAPI_PRODUCT_ID');
        $this->apiToken = env('MAYTAPI_API_TOKEN');
        $this->phoneId = env('MAYTAPI_PHONE_ID');
        $this->baseUrl = "https://api.maytapi.com/api/{$this->productId}/{$this->phoneId}";
    }

    /**
     * Enviar mensaje de texto por WhatsApp
     *
     * @param string $telefono Número de teléfono del destinatario (con código de país, sin símbolos)
     * @param string $mensaje Contenido del mensaje a enviar
     * @return array Respuesta de la API
     */
    public function enviarMensajeTexto($telefono, $mensaje)
    {
        // Limpiar el número de teléfono (eliminar espacios, guiones, paréntesis, etc.)
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        
        $endpoint = "{$this->baseUrl}/sendMessage";
        
        $data = [
            'to_number' => $telefono,
            'type' => 'text',
            'message' => $mensaje
        ];
        
        try {
            $response = Http::withToken($this->apiToken)
                ->withHeaders([
                    'x-maytapi-key' => $this->apiToken
                ])
                ->post($endpoint, $data);
            
            if ($response->successful()) {
                Log::info('Mensaje enviado correctamente a: ' . $telefono);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Error al enviar mensaje: ' . $response->body());
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Excepción al enviar mensaje: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Enviar mensaje con imagen por WhatsApp
     *
     * @param string $telefono Número de teléfono del destinatario
     * @param string $imageUrl URL de la imagen a enviar
     * @param string $caption Texto opcional para acompañar la imagen
     * @return array Respuesta de la API
     */
    public function enviarMensajeImagen($telefono, $imageUrl, $caption = '')
    {
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        
        $endpoint = "{$this->baseUrl}/sendMessage";
        
        $data = [
            'to_number' => $telefono,
            'type' => 'image',
            'message' => $imageUrl,
            'text' => $caption
        ];
        
        try {
            $response = Http::withToken($this->apiToken)
                ->withHeaders([
                    'x-maytapi-key' => $this->apiToken
                ])
                ->post($endpoint, $data);
            
            if ($response->successful()) {
                Log::info('Imagen enviada correctamente a: ' . $telefono);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Error al enviar imagen: ' . $response->body());
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Excepción al enviar imagen: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Enviar mensaje con documento por WhatsApp
     *
     * @param string $telefono Número de teléfono del destinatario
     * @param string $documentUrl URL del documento a enviar
     * @param string $filename Nombre del archivo a mostrar
     * @return array Respuesta de la API
     */
    public function enviarMensajeDocumento($telefono, $documentUrl, $filename = '')
    {
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        
        $endpoint = "{$this->baseUrl}/sendMessage";
        
        $data = [
            'to_number' => $telefono,
            'type' => 'document',
            'message' => $documentUrl,
            'text' => $filename
        ];
        
        try {
            $response = Http::withToken($this->apiToken)
                ->withHeaders([
                    'x-maytapi-key' => $this->apiToken
                ])
                ->post($endpoint, $data);
            
            if ($response->successful()) {
                Log::info('Documento enviado correctamente a: ' . $telefono);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Error al enviar documento: ' . $response->body());
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Excepción al enviar documento: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    // En App\Services\MayTapiService.php

public function enviarMensajeConBotones($telefono, $mensaje, $botones)
{
    $url = "https://api.maytapi.com/api/{$this->productId}/{$this->phoneId}/sendMessage";
    
    $payload = [
        'to_number' => $telefono,
        'type' => 'interactive',
        'interactive' => [
            'type' => 'button',
            'body' => [
                'text' => $mensaje
            ],
            'action' => [
                'buttons' => $botones
            ]
        ]
    ];
    
    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-maytapi-key' => $this->apiToken
        ])->post($url, $payload);
        
        $result = $response->json();
        
        if ($response->successful() && isset($result['success']) && $result['success']) {
            \Log::info('Mensaje con botones enviado correctamente: ' . json_encode($result));
            return ['success' => true, 'data' => $result];
        } else {
            \Log::error('Error al enviar mensaje con botones: ' . json_encode($result));
            return ['success' => false, 'error' => $result['message'] ?? 'Error desconocido'];
        }
    } catch (\Exception $e) {
        \Log::error('Excepción al enviar mensaje con botones: ' . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
    
    /**
     * Verificar el estado de conexión de la instancia de WhatsApp
     *
     * @return array Estado de la instancia
     */
    public function verificarEstado()
    {
        $endpoint = "{$this->baseUrl}/status";
        
        try {
            $response = Http::withToken($this->apiToken)
                ->withHeaders([
                    'x-maytapi-key' => $this->apiToken
                ])
                ->get($endpoint);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}