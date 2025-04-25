<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\MercadoPagoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas API para tu aplicación. Estas rutas se
| cargan a través del RouteServiceProvider y se les asigna el grupo
| de middleware "api", lo que las hace ideales para servicios sin estado.
|
*/

// routes/api.php (para webhooks es mejor usar este archivo)
Route::post('/webhook/whatsapp', [CitaController::class, 'procesarRespuesta']);
// En routes/api.php
Route::get('/webhook/test', function() {
    return response()->json(['status' => 'ok', 'message' => 'Webhook funcionando correctamente']);
});
// Rutas para MercadoPago
Route::post('/mercadopago/preference', [MercadoPagoController::class, 'createPreference']);
