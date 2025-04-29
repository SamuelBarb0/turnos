<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    /**
     * Muestra la lista de mensajes.
     */
    public function index()
    {
        $user = Auth::user();
    
        if ($user->isAdmin()) {
            $mensajes = Mensaje::orderBy('created_at', 'desc')->get();
            $esMisMensajes = false;
        } else {
            $mensajes = Mensaje::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            $esMisMensajes = true;
        }
    
        return view('admin.mensajes.index', compact('mensajes', 'esMisMensajes'));
    }

    /**
     * Mostrar formulario para crear un nuevo mensaje.
     */
    public function create()
    {
        return view('admin.mensajes.create');
    }

    /**
     * Almacena un nuevo mensaje en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:60',
            'body' => 'required|max:900',
        ]);

        Mensaje::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'tipo' => $request->template ?: 'personalizado',
        ]);

        // Verificar si estamos en flujo de configuración
        if ($request->has('setup_flow') && $request->setup_flow) {
            return redirect()->route('admin.setup.complete');
        }

        return redirect()->route('admin.mensajes.index')
            ->with('success', 'Mensaje creado correctamente.');
    }

    /**
     * Muestra formulario para editar un mensaje.
     */
    public function edit($id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $this->authorizeAccess($mensaje);

        return view('admin.mensajes.edit', compact('mensaje'));
    }

    /**
     * Actualiza un mensaje existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:60',
            'body' => 'required|max:900',
        ]);

        $mensaje = Mensaje::findOrFail($id);
        $this->authorizeAccess($mensaje);

        $mensaje->update([
            'title' => $request->title,
            'body' => $request->body,
            'tipo' => $request->template ?: $mensaje->tipo,
        ]);

        return redirect()->route('admin.mensajes.index')
            ->with('success', 'Mensaje actualizado correctamente.');
    }

    /**
     * Elimina un mensaje existente.
     */
    public function destroy($id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $this->authorizeAccess($mensaje);

        $mensaje->delete();

        return redirect()->route('admin.mensajes.index')
            ->with('success', 'Mensaje eliminado correctamente.');
    }

    /**
     * Autorizar acceso a un mensaje (admin puede todo, usuario solo su propio mensaje).
     */
    private function authorizeAccess(Mensaje $mensaje)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $mensaje->user_id !== $user->id) {
            abort(403, 'No tienes permiso para acceder a este mensaje.');
        }
    }
}
