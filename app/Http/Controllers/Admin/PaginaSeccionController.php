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
            // En lugar de usar el disco 'public', guardar directamente en public/images/secciones
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('images/secciones'), $nombreArchivo);
            $data['ruta_image'] = 'images/secciones/' . $nombreArchivo;
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
            if ($seccion->ruta_image && file_exists(public_path($seccion->ruta_image))) {
                unlink(public_path($seccion->ruta_image));
            }

            // Guardar la nueva imagen en public/images/secciones
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('images/secciones'), $nombreArchivo);
            $data['ruta_image'] = 'images/secciones/' . $nombreArchivo;
        }

        $seccion->update($data);

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección actualizada exitosamente');
    }

    public function destroy(Pagina $pagina, PaginaSeccion $seccion)
    {
        // Método destroy:
        if ($seccion->ruta_image && file_exists(public_path($seccion->ruta_image))) {
            unlink(public_path($seccion->ruta_image));
        }

        $seccion->delete();

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección eliminada exitosamente');
    }
}
