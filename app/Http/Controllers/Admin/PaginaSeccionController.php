<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use App\Models\PaginaSeccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaginaSeccionController extends Controller
{
    public function index(Pagina $pagina)
    {
        $secciones = $pagina->secciones()->orderBy('orden')->get();
        return view('admin.secciones.index', compact('pagina', 'secciones'));
    }

    public function create(Pagina $pagina)
    {
        return view('admin.secciones.create', compact('pagina'));
    }

    public function store(Request $request, Pagina $pagina)
    {
        $request->validate([
            'seccion' => 'required',
            'imagen' => 'nullable|image|max:2048',
            'orden' => 'nullable|integer'
        ]);

        $data = $request->all();
        $data['pagina_id'] = $pagina->id;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/secciones
            $rutaDestino = base_path('../public_html/images/secciones');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $data['ruta_image'] = '/images/secciones/' . $nombreArchivo;
        }
        


        PaginaSeccion::create($data);

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección creada exitosamente');
    }

    public function edit(Pagina $pagina, PaginaSeccion $seccion)
    {
        return view('admin.secciones.edit', compact('pagina', 'seccion'));
    }

    public function update(Request $request, Pagina $pagina, PaginaSeccion $seccion)
    {
        $request->validate([
            'seccion' => 'required',
            'imagen' => 'nullable|image|max:2048',
            'orden' => 'nullable|integer'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($seccion->ruta_image && file_exists(base_path('../public_html' . $seccion->ruta_image))) {
                unlink(base_path('../public_html' . $seccion->ruta_image));
            }
            
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $rutaDestino = base_path('../public_html/images/secciones');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $data['ruta_image'] = '/images/secciones/' . $nombreArchivo;
        }

        $seccion->update($data);

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección actualizada exitosamente');
    }

    public function destroy(Pagina $pagina, PaginaSeccion $seccion)
    {
        // Método destroy:
        if ($seccion->ruta_image && file_exists(base_path('../public_html' . $seccion->ruta_image))) {
            unlink(base_path('../public_html' . $seccion->ruta_image));
        }

        $seccion->delete();

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección eliminada exitosamente');
    }
}
