<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    /**
     * Muestra la lista de mensajes del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mensajes = Mensaje::where('user_id', Auth::id())->get();
        return view('admin.mensajes.index', compact('mensajes'));
    }

    /**
     * Muestra el formulario para crear un nuevo mensaje.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.mensajes.create');
    }

    /**
     * Almacena un nuevo mensaje en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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

        // Verificar si estamos en el flujo de configuración
        if ($request->has('setup_flow') && $request->setup_flow) {
            return redirect()->route('admin.setup.complete');
        }

        return redirect()->route('mensajes.index')
            ->with('success', 'Mensaje creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un mensaje existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $mensaje = Mensaje::where('user_id', Auth::id())->findOrFail($id);
        return view('admin.mensajes.edit', compact('mensaje'));
    }

    /**
     * Actualiza un mensaje específico en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:60',
            'body' => 'required|max:900',
        ]);

        $mensaje = Mensaje::where('user_id', Auth::id())->findOrFail($id);
        
        $mensaje->update([
            'title' => $request->title,
            'body' => $request->body,
            'tipo' => $request->template ?: $mensaje->tipo,
        ]);

        return redirect()->route('mensajes.index')
            ->with('success', 'Mensaje actualizado correctamente.');
    }

    /**
     * Elimina un mensaje específico de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $mensaje = Mensaje::where('user_id', Auth::id())->findOrFail($id);
        $mensaje->delete();

        return redirect()->route('mensajes.index')
            ->with('success', 'Mensaje eliminado correctamente.');
    }
}