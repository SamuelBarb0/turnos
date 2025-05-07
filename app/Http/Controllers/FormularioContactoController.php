<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoFormulario;
use App\Models\Contacto;

class FormularioContactoController extends Controller
{
    /**
     * Muestra la página de contacto con datos dinámicos.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $contacto = Contacto::first();
        
        // Si no existe contacto en la base de datos, inicializa un objeto vacío
        if (!$contacto) {
            $contacto = new Contacto();
        }
        
        return view('contacto', compact('contacto'));
    }

    /**
     * Procesa el envío del formulario de contacto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enviar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        // Obtener la información de contacto para saber a dónde enviar el correo
        $contactoInfo = Contacto::first();
        $destinatario = $contactoInfo ? $contactoInfo->email : config('mail.from.address');

        // Enviar el correo electrónico
        Mail::to($destinatario)->send(new ContactoFormulario($request->all()));

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('contacto')->with('success', '¡Gracias por contactarnos! Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo lo antes posible.');
    }
}