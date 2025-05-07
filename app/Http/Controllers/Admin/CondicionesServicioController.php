<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CondicionesServicio;
use App\Models\SeoMetadata;

class CondicionesServicioController extends Controller
{
    /**
     * Mostrar en el FRONT las condiciones de servicio
     */
    public function show()
    {
        // Obtener contenido de condiciones
        $condiciones = CondicionesServicio::first();

        // Obtener SEO para las condiciones
        $seo = SeoMetadata::where('page_slug', 'condiciones')
                          ->orWhere('page_slug', '/condiciones-de-servicio')
                          ->first();

        return view('condiciones-servicio', compact('condiciones', 'seo'));
    }

    /**
     * Mostrar en el admin (editar)
     */
    public function edit()
    {
        $condiciones = CondicionesServicio::first();

        return view('admin.paginas.index', compact('condiciones'));
    }

    /**
     * Actualizar contenido en el admin
     */
    public function update(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string',
        ]);

        $condiciones = CondicionesServicio::first();

        if (!$condiciones) {
            $condiciones = new CondicionesServicio();
        }

        $condiciones->contenido = $request->input('contenido');
        $condiciones->save();

        return redirect()->back()->with('success', 'Las condiciones de servicio han sido actualizadas correctamente.');
    }
}