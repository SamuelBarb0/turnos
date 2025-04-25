<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class WelcomeController extends Controller
{

    
    /**
     * Mostrar la vista de bienvenida.
     */
    public function showWelcome(): View
    {
        return view('admin.setup.welcome');
    }

    /**
     * Mostrar la vista del plan gratuito.
     */
    public function showFreePlan(): View
    {
        return view('admin.setup.free-plan');
    }
    
    /**
     * Procesar la redirección de bienvenida a plan gratuito.
     */
    public function processWelcome(): RedirectResponse
    {
        return redirect()->route('admin.setup.free-plan');
    }
    
    /**
     * Marcar la configuración como completada y redirigir al dashboard.
     */
    public function completeSetup(Request $request): RedirectResponse
    {
        // Obtener el usuario actual
        $user = $request->user();
        
        // Marcar la configuración como completada
        $user->setup_completed = true;
        $user->save();
        
        // Redirigir al dashboard
        return redirect()->route('dashboard');
    }
}