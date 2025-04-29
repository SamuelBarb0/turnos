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
        return Socialite::driver('google')
        ->scopes(['openid', 'profile', 'email', 'https://www.googleapis.com/auth/calendar'])
        ->redirect();

    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Buscar usuario por email o google_id
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();
            
            if ($user) {
                // Actualizar tokens si ya existe
                $user->update([
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'token_expiration' => now()->addSeconds($googleUser->expiresIn),
                ]);
    
                Auth::login($user);
    
                if ($user->setup_completed) {
                    return redirect()->intended('/dashboard');
                }
    
                if ($user->isFree()) {
                    return redirect()->route('admin.setup.welcome');
                }
    
                return redirect()->intended('/dashboard');
            } else {
                // Crear nuevo usuario
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'token_expiration' => now()->addSeconds($googleUser->expiresIn),
                    'password' => bcrypt(rand(1, 10000)),
                    'role' => User::ROLE_FREE,
                    'setup_completed' => false,
                ]);
    
                Auth::login($newUser);
    
                return redirect()->route('admin.setup.welcome');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Ha ocurrido un error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
    
}