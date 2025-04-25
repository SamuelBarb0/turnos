<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\RedirectResponse;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar usuario por email o google_id
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();
            
            if ($user) {
                // Si el usuario existe pero no tiene google_id, actualizarlo
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                
                Auth::login($user);
                
                // Si el usuario ya completó el setup, ir al dashboard
                if ($user->setup_completed) {
                    return redirect()->intended('/dashboard');
                }
                
                // Si no ha completado el setup y es un usuario free, ir a la bienvenida
                if ($user->isFree()) {
                    return redirect()->route('admin.setup.welcome');
                }
                
                // Si no es free, ir al dashboard
                return redirect()->intended('/dashboard');
            } else {
                // Crear nuevo usuario
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(rand(1, 10000)),
                    'role' => User::ROLE_FREE, // Asignar rol free por defecto
                    'setup_completed' => false, // Usuario nuevo, necesita completar el setup
                ]);
                
                Auth::login($newUser);
                
                // Redirigir a la bienvenida
                return redirect()->route('admin.setup.welcome');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Ha ocurrido un error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}