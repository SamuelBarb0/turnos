<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Pagina; // Importamos el modelo Pagina
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    /**
     * Mostrar la información de contacto en el panel de administración.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contacto = Contacto::first();
        $paginas = Pagina::all(); // Obtenemos todas las páginas
        
        return view('admin.paginas.index', compact('contacto', 'paginas'));
    }

    /**
     * Actualizar la información de contacto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'contact_tag' => 'required|string|max:255',
            'contact_title' => 'required|string|max:255',
            'contact_description' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'contact_hours' => 'nullable|string|max:255',
            'contact_facebook' => 'nullable|url|max:255',
            'contact_instagram' => 'nullable|url|max:255',
            'contact_twitter' => 'nullable|url|max:255',
            'contact_whatsapp' => 'nullable|url|max:255',
        ]);

        $contacto = Contacto::firstOrNew();
        
        $contacto->tag = $request->contact_tag;
        $contacto->title = $request->contact_title;
        $contacto->description = $request->contact_description;
        $contacto->phone = $request->contact_phone;
        $contacto->email = $request->contact_email;
        $contacto->address = $request->contact_address;
        $contacto->hours = $request->contact_hours;
        $contacto->facebook = $request->contact_facebook;
        $contacto->instagram = $request->contact_instagram;
        $contacto->twitter = $request->contact_twitter;
        $contacto->whatsapp = $request->contact_whatsapp;
        
        $contacto->save();

        return redirect()->route('admin.paginas.index')->with('success', 'Información de contacto actualizada correctamente.');
    }

    /**
     * Mostrar la página de contacto en el frontend.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $contacto = Contacto::first();
        
        return view('contacto', compact('contacto'));
    }
}