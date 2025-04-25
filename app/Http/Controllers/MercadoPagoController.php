<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller
{
    public function createPreference(Request $request)
    {
        try {
            // Log de entrada para depuración
            Log::info('Iniciando createPreference', [
                'title' => $request->title,
                'price' => $request->price
            ]);
            
            // Configurar SDK con tu token de acceso
            $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
            if (empty($accessToken)) {
                Log::error('MERCADOPAGO_ACCESS_TOKEN no está configurado');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token de acceso no configurado'
                ], 500);
            }
            
            MercadoPagoConfig::setAccessToken($accessToken);
            
            // Generar una referencia externa única
            $external_reference = 'ORDER-' . time() . '-' . rand(1000, 9999);
            
            // IMPORTANTE: Determinar la moneda correcta según el país
            // Argentina: ARS
            // Brasil: BRL
            // México: MXN
            // Colombia: COP
            // Chile: CLP
            // Uruguay: UYU
            // Perú: PEN
            // Usa la que corresponda a tu país
            $currency_id = 'ARS'; // Cambia esto según tu país
            
            // Crear la estructura de la preferencia según documentación
            $preference_data = [
                "items" => [
                    [
                        "id" => "item-" . rand(1000, 9999),
                        "title" => $request->title,
                        "quantity" => 1,
                        "unit_price" => (float) $request->price,
                        "currency_id" => $currency_id
                    ]
                ],
                "back_urls" => [
                    "success" => url('/payment/success'),
                    "failure" => url('/payment/failure'),
                    "pending" => url('/payment/pending')
                ],
                "statement_descriptor" => "Servicio de citas",
                "external_reference" => $external_reference
            ];
            
            Log::info('Preference data', $preference_data);
            
            // Cliente de preferencias
            $client = new PreferenceClient();
            
            // Crear la preferencia
            $preference = $client->create($preference_data);
            
            Log::info('Preference created', [
                'id' => $preference->id,
                'init_point' => $preference->init_point
            ]);
            
            // Éxito
            return response()->json([
                'status' => 'success',
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point
            ]);
            
        } catch (MPApiException $e) {
            // Log detallado del error de MercadoPago
            Log::error('MPApiException', [
                'message' => $e->getMessage(),
                'status_code' => $e->getApiResponse()->getStatusCode(),
                'content' => $e->getApiResponse()->getContent()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error de MercadoPago: ' . $e->getMessage(),
                'details' => $e->getApiResponse()->getContent()
            ], 500);
            
        } catch (\Exception $e) {
            // Log detallado de cualquier otro error
            Log::error('Exception en createPreference', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}