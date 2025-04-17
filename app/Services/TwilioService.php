<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $fromNumber;
    
    public function __construct()
    {
        // Configurar cliente de Twilio
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->fromNumber = config('services.twilio.whatsapp_from');
        
        try {
            $this->client = new Client($sid, $token);
        } catch (Exception $e) {
            Log::error('Error al inicializar Twilio: ' . $e->getMessage());
        }
    }
    
    public function enviarMensajeConBotones($to, $mensaje, $buttons)
    {
        try {
            // Asegurarse de que el número tiene el formato de WhatsApp
            if (!str_starts_with($to, 'whatsapp:+')) {
                $to = 'whatsapp:+' . $to;
            }
            
            // Crear el objeto de mensaje interactivo con botones
            $messageOptions = [
                'from' => 'whatsapp:' . $this->fromNumber,
                'body' => $mensaje,
            ];
            
            // Si estamos usando la API de WhatsApp Business (no Sandbox)
            if (config('services.twilio.use_whatsapp_api', false)) {
                // Formato para la API de WhatsApp Business
                $buttonItems = [];
                foreach ($buttons as $button) {
                    $buttonItems[] = [
                        'type' => 'reply',
                        'reply' => [
                            'id' => $button['id'],
                            'title' => $button['text']
                        ]
                    ];
                }
                
                $messageOptions['contentSid'] = $this->createMessageTemplate($mensaje, $buttonItems);
            } else {
                // Formato para el Sandbox (usando el cuerpo del mensaje para instrucciones)
                $messageOptions['body'] .= "\n\nResponde con una de estas opciones:";
                foreach ($buttons as $index => $button) {
                    $messageOptions['body'] .= "\n" . ($index + 1) . ". " . $button['text'];
                }
            }
            
            // Enviar el mensaje
            $message = $this->client->messages->create($to, $messageOptions);
            
            Log::info('Mensaje enviado: ' . $message->sid);
            return [
                'success' => true,
                'message_sid' => $message->sid
            ];
        } catch (Exception $e) {
            Log::error('Error al enviar mensaje: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    // Este método solo es necesario si estás usando la API completa de WhatsApp Business
    private function createMessageTemplate($mensaje, $buttonItems)
    {
        try {
            // Crear una plantilla de contenido con botones
            $contentResource = $this->client->content->v1->contents->create([
                'friendlyName' => 'Interactive Message ' . time(),
                'contentType' => 'message/interactive',
                'content' => json_encode([
                    'type' => 'button',
                    'body' => [
                        'text' => $mensaje
                    ],
                    'action' => [
                        'buttons' => $buttonItems
                    ]
                ])
            ]);
            
            return $contentResource->sid;
        } catch (Exception $e) {
            Log::error('Error al crear plantilla de mensaje: ' . $e->getMessage());
            throw $e;
        }
    }
}