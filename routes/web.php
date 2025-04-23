<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\PaginaController;
use App\Http\Controllers\Admin\PaginaSeccionController;
use App\Http\Controllers\Admin\ContenidoSeccionController;
use Illuminate\Support\Facades\Route;

// Importa las rutas de autenticación primero
require __DIR__.'/auth.php';

Route::get('/', function () {
    $pagina = \App\Models\Pagina::where('slug', 'inicio')->orWhere('slug', '')->first();
    
    // Si no existe una página, muestra welcome sin datos dinámicos
    if (!$pagina) {
        return view('welcome');
    }
    
    // Si existe la página, pasa la variable a la vista
    return view('welcome', compact('pagina'));
});

// Rutas de autenticación con Google
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/dashboard', function () {
    return redirect()->route('admin.paginas.index');
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

// Rutas del panel de administración para el CMS
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Gestión de Páginas
    Route::resource('paginas', PaginaController::class);

    // Gestión de Secciones de Página
    Route::get('paginas/{pagina}/secciones', [PaginaSeccionController::class, 'index'])
        ->name('paginas.secciones.index');
    Route::get('paginas/{pagina}/secciones/create', [PaginaSeccionController::class, 'create'])
        ->name('paginas.secciones.create');
    Route::post('paginas/{pagina}/secciones', [PaginaSeccionController::class, 'store'])
        ->name('paginas.secciones.store');
    Route::get('paginas/{pagina}/secciones/{seccion}/edit', [PaginaSeccionController::class, 'edit'])
        ->name('paginas.secciones.edit');
    Route::put('paginas/{pagina}/secciones/{seccion}', [PaginaSeccionController::class, 'update'])
        ->name('paginas.secciones.update');
    Route::delete('paginas/{pagina}/secciones/{seccion}', [PaginaSeccionController::class, 'destroy'])
        ->name('paginas.secciones.destroy');

    // Gestión de Contenidos de Secciones
    Route::get('secciones/{seccion}/contenidos', [ContenidoSeccionController::class, 'index'])
        ->name('secciones.contenidos.index');
    Route::get('secciones/{seccion}/contenidos/create', [ContenidoSeccionController::class, 'create'])
        ->name('secciones.contenidos.create');
    Route::post('secciones/{seccion}/contenidos', [ContenidoSeccionController::class, 'store'])
        ->name('secciones.contenidos.store');
    Route::get('secciones/{seccion}/contenidos/{contenido}/edit', [ContenidoSeccionController::class, 'edit'])
        ->name('secciones.contenidos.edit');
    Route::put('secciones/{seccion}/contenidos/{contenido}', [ContenidoSeccionController::class, 'update'])
        ->name('secciones.contenidos.update');
    Route::delete('secciones/{seccion}/contenidos/{contenido}', [ContenidoSeccionController::class, 'destroy'])
        ->name('secciones.contenidos.destroy');
});

// Ruta para mostrar páginas dinámicas - ESTA DEBE SER LA ÚLTIMA RUTA
Route::get('/{slug}', function ($slug) {
    $pagina = \App\Models\Pagina::where('slug', $slug)->firstOrFail();
    return view('paginas.show', compact('pagina'));
})->name('paginas.show')->where('slug', '^(?!login|register|password).*$');