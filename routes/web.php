<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas CRUD para citas
Route::resource('citas', CitaController::class);

// Ruta para sincronización con Google Calendar
Route::get('/citas/sincronizar-calendario', [CitaController::class, 'sincronizarCalendario'])
    ->name('citas.sincronizar');

// Rutas para mensajes de WhatsApp
Route::get('/citas/{cita}/preparar-mensaje', [CitaController::class, 'prepararMensaje'])
    ->name('citas.preparar.mensaje');

Route::post('/citas/{cita}/enviar-mensaje', [CitaController::class, 'enviarMensaje'])
    ->name('citas.enviar.mensaje');


Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
