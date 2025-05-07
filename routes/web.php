<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\PaginaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WelcomeController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SeoMetadataController;
use App\Http\Controllers\Admin\PaginaSeccionController;
use App\Http\Controllers\Admin\ContenidoSeccionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\DashboardController; // Nuevo controlador para el dashboard personalizado
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\FormularioContactoController;
use App\Http\Controllers\Admin\ContactoController;
use App\Http\Controllers\Admin\PoliticaPrivacidadController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\FreePlanMiddleware;
use Illuminate\Http\Request;

// Importa las rutas de autenticación primero
require __DIR__ . '/auth.php';

Route::get('/', function () {
    $pagina = \App\Models\Pagina::where('slug', 'inicio')->orWhere('slug', '')->first();

    // Obtener metadatos SEO para la página de inicio
    $seo = \App\Models\SeoMetadata::where('page_slug', 'home')
        ->orWhere('page_slug', '/')
        ->first();

    // Si no existe una página, muestra welcome sin datos dinámicos
    if (!$pagina) {
        return view('welcome', compact('seo'));
    }

    // Si existe la página, pasa las variables a la vista
    return view('welcome', compact('pagina', 'seo'));
})->name('home');

// Rutas para el blog (frontend) - Ahora usan el controlador
Route::get('/blog', [BlogController::class, 'showBlog'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'showArticulo'])->name('blog.show');

Route::get('/politica-de-privacidad', [PoliticaPrivacidadController::class, 'show'])->name('politica');

// Rutas de autenticación con Google
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/contacto', [ContactoController::class, 'show'])->name('contacto');
Route::get('/contacto', [FormularioContactoController::class, 'show'])->name('contacto');
Route::post('/contacto/enviar', [FormularioContactoController::class, 'enviar'])->name('contacto.enviar');


// Rutas para usuarios con plan gratuito - Configuración inicial
Route::middleware(['auth', FreePlanMiddleware::class])->group(function () {
    Route::get('/admin/setup/welcome', [WelcomeController::class, 'showWelcome'])
        ->name('admin.setup.welcome');
    Route::get('/admin/setup/free-plan', [WelcomeController::class, 'showFreePlan'])
        ->name('admin.setup.free-plan');
    Route::get('/admin/setup/process-welcome', [WelcomeController::class, 'processWelcome'])
        ->name('admin.setup.process-welcome');
    Route::post('/admin/setup/complete', [WelcomeController::class, 'completeSetup'])
        ->name('admin.setup.complete');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.paginas.index');
    }

    if ($user->setup_completed) {
        return app(DashboardController::class)->index();
    }

    // En vez de intentar mostrar 'dashboard', redirige seguro a su dashboard si no completó
    return redirect()->route('admin.setup.welcome');
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // CRUD de mensajes
    Route::resource('mensajes', MensajeController::class)
        ->except(['show']); // No necesitas "show" si no estás viendo un solo mensaje

});

// Grupo de rutas del dashboard (usuarios normales) 
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    // Dashboard principal (Perfil + Resumen)
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Perfil de usuario (Actualizar)
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // ========== Plantillas de Mensajes ==========
    Route::prefix('mensajes')->name('mensajes.')->group(function () {
        Route::get('/', [MensajeController::class, 'index'])->name('index'); // Listar mensajes
        Route::get('/create', [MensajeController::class, 'create'])->name('create'); // Formulario crear
        Route::post('/', [MensajeController::class, 'store'])->name('store'); // Guardar nuevo mensaje
        Route::get('/{mensaje}/edit', [MensajeController::class, 'edit'])->name('edit'); // Editar mensaje
        Route::put('/{mensaje}', [MensajeController::class, 'update'])->name('update'); // Actualizar mensaje
        Route::delete('/{mensaje}', [MensajeController::class, 'destroy'])->name('destroy'); // Eliminar mensaje
    });

    // ========== Citas ==========
    Route::prefix('citas')->name('citas.')->group(function () {
        Route::get('/', [CitaController::class, 'index'])->name('index'); // Listar citas
        Route::get('/{cita}', [CitaController::class, 'show'])->name('show'); // Ver cita
        Route::post('/{cita}/confirm', [DashboardController::class, 'confirmCita'])->name('confirm'); // Confirmar cita (AJAX)
        Route::post('/{cita}/cancel', [DashboardController::class, 'cancelCita'])->name('cancel'); // Cancelar cita (AJAX)
        Route::post('/{cita}/send-reminder', [DashboardController::class, 'sendReminderMessage'])->name('reminder'); // Enviar recordatorio (AJAX)
    });
});

// Rutas para MercadoPago con middleware de autenticación
Route::middleware('auth')->group(function () {
    // Solo usuarios autenticados pueden crear preferencias de pago
    Route::post('/mercadopago/preference', [MercadoPagoController::class, 'createPreference']);
});

// Las rutas de retorno pueden ser accesibles sin autenticación
Route::get('/payment/success', [MercadoPagoController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failure', [MercadoPagoController::class, 'paymentFailure'])->name('payment.failure');
Route::get('/payment/pending', [MercadoPagoController::class, 'paymentPending'])->name('payment.pending');

// El webhook debe ser público para que MercadoPago pueda enviar notificaciones
Route::post('/api/mercadopago/webhook', [MercadoPagoController::class, 'webhook']);

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

    Route::get('/politica', [PoliticaPrivacidadController::class, 'edit'])->name('politica.edit');
    Route::put('/politica', [PoliticaPrivacidadController::class, 'update'])->name('politica.update');

    Route::get('/seo', [SeoMetadataController::class, 'index'])->name('seo.index');
    Route::get('/seo/create', [SeoMetadataController::class, 'create'])->name('seo.create');
    Route::post('/seo', [SeoMetadataController::class, 'store'])->name('seo.store');
    Route::get('/seo/{id}/edit', [SeoMetadataController::class, 'edit'])->name('seo.edit');
    Route::put('/seo/{id}', [SeoMetadataController::class, 'update'])->name('seo.update');
    Route::delete('/seo/{id}', [SeoMetadataController::class, 'destroy'])->name('seo.destroy');

    // Gestión del blog (CRUD)
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
    Route::post('/blog/settings', [BlogController::class, 'updateSettings'])->name('blog.settings.update');
    Route::post('/blog/upload-image', [BlogController::class, 'uploadImage'])->name('blog.upload-image');

    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

    // Administración de pagos
    Route::get('/payments/dashboard', [PaymentController::class, 'dashboard'])->name('payments.dashboard');
    Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');

    Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
    Route::put('/contacto', [ContactoController::class, 'update'])->name('contacto.update');
});

// Ruta para mostrar páginas dinámicas - ESTA DEBE SER LA ÚLTIMA RUTA
Route::get('/{slug}', function ($slug) {
    $pagina = \App\Models\Pagina::where('slug', $slug)->firstOrFail();
    return view('paginas.show', compact('pagina'));
})->name('paginas.show')->where('slug', '^(?!login|register|password|blog).*$');
