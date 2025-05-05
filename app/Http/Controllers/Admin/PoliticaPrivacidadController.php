<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PoliticaPrivacidad;
use App\Models\SeoMetadata;

class PoliticaPrivacidadController extends Controller
{
    /**
     * Mostrar en el FRONT la política de privacidad
     */
    public function show()
    {
        // Obtener contenido de política
        $politica = PoliticaPrivacidad::first();

        // Obtener SEO para la política
        $seo = SeoMetadata::where('page_slug', 'politica')
                          ->orWhere('page_slug', '/politica-de-privacidad')
                          ->first();

        return view('politica-privacidad', compact('politica', 'seo'));
    }

    /**
     * Mostrar en el admin (editar)
     */
    public function edit()
    {
        $politica = PoliticaPrivacidad::first();

        return view('admin.paginas.index', compact('politica'));
    }

    /**
     * Actualizar contenido en el admin
     */
    public function update(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string',
        ]);

        $politica = PoliticaPrivacidad::first();

        if (!$politica) {
            $politica = new PoliticaPrivacidad();
        }

        $politica->contenido = $request->input('contenido');
        $politica->save();

        return redirect()->back()->with('success', 'La política de privacidad ha sido actualizada correctamente.');
    }
}
