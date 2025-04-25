<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        // No usamos middleware directamente aquí
    }

    /**
     * Mostrar la lista de usuarios
     */
    public function index(Request $request)
    {
        // Verificar si el usuario es admin
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Acceso no autorizado');
        }

        $query = User::query();

        // Filtrar por búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtrar por rol
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Obtener los usuarios con paginación
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Actualizar el rol de un usuario
     */
    public function updateRole(Request $request, $id)
    {
        // Verificar si el usuario es admin
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Acceso no autorizado');
        }

        $user = User::findOrFail($id);
        
        // Validar rol
        $request->validate([
            'role' => 'required|integer|in:' . implode(',', [
                User::ROLE_FREE,
                User::ROLE_BASIC,
                User::ROLE_PREMIUM,
                User::ROLE_ADMIN
            ]),
        ]);

        // Actualizar rol
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Rol de usuario {$user->name} actualizado correctamente");
    }

    /**
     * Eliminar un usuario
     */
    public function delete($id)
    {
        // Verificar si el usuario es admin
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Acceso no autorizado');
        }

        $user = User::findOrFail($id);
        
        // Evitar eliminar al usuario actual
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario');
        }

        // Evitar eliminar a otros administradores
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar a otro administrador');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario {$userName} eliminado correctamente");
    }
}