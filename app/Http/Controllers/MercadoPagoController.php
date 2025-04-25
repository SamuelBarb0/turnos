<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
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
                "notification_url" => url('/api/mercadopago/webhook'), // URL para notificaciones IPN
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
            
            // Guardar la información del pago en la base de datos
            $payment = Payment::create([
                'preference_id' => $preference->id,
                'external_reference' => $external_reference,
                'title' => $request->title,
                'amount' => (float) $request->price,
                'currency' => $currency_id,
                'status' => 'pending',
                'user_id' => auth()->check() ? auth()->id() : null, // Si hay un usuario autenticado
                'related_id' => $request->related_id ?? null, // ID relacionado (e.g., ID de cita)
                'related_type' => $request->related_type ?? null // Tipo de relación (e.g., 'App\Models\Cita')
            ]);
            
            Log::info('Payment record created', ['payment_id' => $payment->id]);
            
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
    
    /**
     * Manejador de notificaciones webhook de MercadoPago
     */
    public function webhook(Request $request)
    {
        try {
            // Log de entrada para depuración
            Log::info('Recibida notificación de MercadoPago', $request->all());
            
            // El tipo de notificación debe venir en la solicitud
            if (!$request->has('type')) {
                Log::warning('Webhook recibido sin tipo de notificación', $request->all());
                return response()->json(['status' => 'error', 'message' => 'Tipo de notificación no especificado'], 400);
            }
            
            $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
            if (empty($accessToken)) {
                Log::error('MERCADOPAGO_ACCESS_TOKEN no está configurado para webhook');
                return response()->json(['status' => 'error', 'message' => 'Token no configurado'], 500);
            }
            
            MercadoPagoConfig::setAccessToken($accessToken);
            
            // Tipo de notificación
            $type = $request->input('type');
            
            // Si es una notificación de pago
            if ($type === 'payment') {
                // El id de pago debería estar en data.id
                if (!$request->has('data') || !isset($request->data['id'])) {
                    Log::warning('Notificación de pago sin ID de pago', $request->all());
                    return response()->json(['status' => 'error', 'message' => 'ID de pago no proporcionado'], 400);
                }
                
                $paymentId = $request->input('data.id');
                if (empty($paymentId)) {
                    $paymentId = $request->data['id']; // Intentar obtener de otra forma
                }
                
                // Obtener información del pago desde la API de MercadoPago
                $client = new PaymentClient();
                try {
                    $paymentInfo = $client->get($paymentId);
                    Log::info('Información del pago obtenida', (array) $paymentInfo);
                    
                    // Buscar el registro de pago correspondiente
                    // Primero intentar por preference_id
                    $payment = null;
                    if (isset($paymentInfo->preference_id)) {
                        $payment = Payment::where('preference_id', $paymentInfo->preference_id)->first();
                    }
                    
                    // Si no se encuentra, intentar por external_reference
                    if (!$payment && isset($paymentInfo->external_reference)) {
                        $payment = Payment::where('external_reference', $paymentInfo->external_reference)->first();
                    }
                    
                    if ($payment) {
                        // Actualizar el registro del pago
                        $payment->payment_id = $paymentId;
                        $payment->status = $paymentInfo->status;
                        $payment->payment_details = json_encode($paymentInfo);
                        $payment->save();
                        
                        Log::info('Registro de pago actualizado', [
                            'payment_id' => $payment->id,
                            'status' => $payment->status
                        ]);
                        
                        // Si hay una relación con otro modelo (Cita, Servicio, etc.)
                        if ($payment->related_id && $payment->related_type) {
                            try {
                                $relatedModel = $payment->related_type::find($payment->related_id);
                                if ($relatedModel) {
                                    // Actualizamos el estado de pago en el modelo relacionado
                                    // Asumiendo que tiene un campo payment_status
                                    if (property_exists($relatedModel, 'payment_status')) {
                                        $relatedModel->payment_status = $payment->status;
                                        $relatedModel->save();
                                        
                                        Log::info('Estado de pago actualizado en modelo relacionado', [
                                            'model_type' => $payment->related_type,
                                            'model_id' => $payment->related_id
                                        ]);
                                    }
                                }
                            } catch (\Exception $e) {
                                Log::error('Error al actualizar modelo relacionado', [
                                    'message' => $e->getMessage(),
                                    'related_type' => $payment->related_type,
                                    'related_id' => $payment->related_id
                                ]);
                            }
                        }
                    } else {
                        Log::warning('No se encontró un registro de pago para la preferencia', [
                            'preference_id' => $paymentInfo->preference_id ?? 'no disponible',
                            'external_reference' => $paymentInfo->external_reference ?? 'no disponible'
                        ]);
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Error al obtener información del pago', [
                        'payment_id' => $paymentId,
                        'message' => $e->getMessage()
                    ]);
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'Error al obtener información del pago: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                // Otro tipo de notificación (no payment)
                Log::info('Notificación no procesada (no es de tipo payment)', [
                    'type' => $type
                ]);
            }
            
            // Siempre devolver éxito para que MercadoPago no reintente
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Error al procesar webhook de MercadoPago', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Siempre devolver en formato JSON
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Manejador para el retorno de pago exitoso
     */
    public function paymentSuccess(Request $request)
    {
        try {
            Log::info('Retorno de pago exitoso', $request->all());
            
            $paymentId = $request->input('payment_id');
            $status = $request->input('status');
            $externalReference = $request->input('external_reference');
            
            // Actualizar el estado del pago si es necesario
            if ($paymentId) {
                $payment = Payment::where('payment_id', $paymentId)
                    ->orWhere('external_reference', $externalReference)
                    ->first();
                
                if ($payment) {
                    $payment->status = $status ?? 'approved';
                    $payment->save();
                    
                    Log::info('Estado de pago actualizado en retorno exitoso', [
                        'payment_id' => $payment->id,
                        'status' => $payment->status
                    ]);
                }
            }
            
            // Redirigir a la página de éxito o mostrar una vista
            return view('payment.success', [
                'payment_id' => $paymentId,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Log::error('Error en paymentSuccess', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // En caso de error, mostrar la vista de éxito de todos modos
            return view('payment.success');
        }
    }
    
    /**
     * Manejador para el retorno de pago fallido
     */
    public function paymentFailure(Request $request)
    {
        try {
            Log::info('Retorno de pago fallido', $request->all());
            
            // Actualizar el estado del pago si es necesario
            $paymentId = $request->input('payment_id');
            $externalReference = $request->input('external_reference');
            
            if ($paymentId || $externalReference) {
                $payment = Payment::where(function($query) use ($paymentId, $externalReference) {
                    if ($paymentId) {
                        $query->where('payment_id', $paymentId);
                    }
                    if ($externalReference) {
                        $query->orWhere('external_reference', $externalReference);
                    }
                })->first();
                
                if ($payment) {
                    $payment->status = 'rejected';
                    $payment->save();
                    
                    Log::info('Estado de pago actualizado en retorno fallido', [
                        'payment_id' => $payment->id,
                        'status' => $payment->status
                    ]);
                }
            }
            
            // Redirigir a la página de fallo o mostrar una vista
            return view('payment.failure');
        } catch (\Exception $e) {
            Log::error('Error en paymentFailure', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // En caso de error, mostrar la vista de fallo de todos modos
            return view('payment.failure');
        }
    }
    
    /**
     * Manejador para el retorno de pago pendiente
     */
    public function paymentPending(Request $request)
    {
        try {
            Log::info('Retorno de pago pendiente', $request->all());
            
            // Actualizar el estado del pago si es necesario
            $paymentId = $request->input('payment_id');
            $externalReference = $request->input('external_reference');
            
            if ($paymentId || $externalReference) {
                $payment = Payment::where(function($query) use ($paymentId, $externalReference) {
                    if ($paymentId) {
                        $query->where('payment_id', $paymentId);
                    }
                    if ($externalReference) {
                        $query->orWhere('external_reference', $externalReference);
                    }
                })->first();
                
                if ($payment) {
                    $payment->status = 'pending';
                    $payment->save();
                    
                    Log::info('Estado de pago actualizado en retorno pendiente', [
                        'payment_id' => $payment->id,
                        'status' => $payment->status
                    ]);
                }
            }
            
            // Redirigir a la página de pendiente o mostrar una vista
            return view('payment.pending');
        } catch (\Exception $e) {
            Log::error('Error en paymentPending', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // En caso de error, mostrar la vista de pendiente de todos modos
            return view('payment.pending');
        }
    }
}